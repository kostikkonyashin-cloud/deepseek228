<?php

namespace App\Http\Controllers\Web\Admin\Export;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminExportController extends Controller
{
    /**
     * Экспорт покупок за период
     */
    public function exportOrders(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Валидация дат
        $errors = [];
        
        if ($startDate && !strtotime($startDate)) {
            $errors[] = 'Некорректная дата "От"';
        }
        
        if ($endDate && !strtotime($endDate)) {
            $errors[] = 'Некорректная дата "До"';
        }
        
        if ($startDate && $endDate && strtotime($startDate) > strtotime($endDate)) {
            $errors[] = 'Дата "От" не может быть позже даты "До"';
        }
        
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'errors' => $errors
            ], 422);
        }

        $orders = Order::with(['user', 'orderItems.product'])
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->orderBy('created_at', 'desc')
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'errors' => ['За указанный период заказов не найдено']
            ], 404);
        }

        // Создаем Excel файл
        $spreadsheet = new Spreadsheet();
        
        // Настройка заголовков
        $spreadsheet->getProperties()
            ->setCreator('Магазин')
            ->setLastModifiedBy('Магазин')
            ->setTitle('Отчет по заказам')
            ->setSubject('Отчет по заказам')
            ->setDescription('Отчет по заказам за период');

        // Получаем активный лист
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Заказы');

        // Заголовок отчета
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'ОТЧЕТ ПО ЗАКАЗАМ');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Период
        $periodText = 'Период: ';
        $periodText .= $startDate ? Carbon::parse($startDate)->format('d.m.Y') : 'с начала';
        $periodText .= ' - ';
        $periodText .= $endDate ? Carbon::parse($endDate)->format('d.m.Y') : 'по сегодня';
        
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', $periodText);
        $sheet->getStyle('A2')->getFont()->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Дата генерации
        $sheet->setCellValue('A3', 'Дата генерации: ' . Carbon::now()->format('d.m.Y H:i:s'));
        $sheet->getStyle('A3')->getFont()->setSize(10);
        
        // Пустая строка
        $sheet->getStyle('A4:G4')->getFont()->setSize(8);
        
        // Заголовки столбцов
        $headers = ['ID', 'Номер заказа', 'Дата', 'Клиент', 'Сумма (₽)', 'Статус', 'Состав заказа'];
        $column = 'A';
        foreach ($headers as $index => $header) {
            $cell = $column . '6';
            $sheet->setCellValue($cell, $header);
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $sheet->getStyle($cell)->getFont()->setBold(true)->setSize(11);
            $sheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('2c3e50');
            $sheet->getStyle($cell)->getFont()->getColor()->setRGB('ffffff');
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $column++;
        }
        
        // Стиль для границ
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'dddddd']
                ]
            ]
        ];
        
        // Заполнение данными
        $row = 7;
        $totalAmount = 0;
        
        foreach ($orders as $order) {
            $items = $order->orderItems->map(fn($item) => 
                $item->product->name . " ({$item->quantity} шт. x " . number_format($item->price, 0, '.', ' ') . " ₽)"
            )->implode("\n");
            
            $sheet->setCellValue('A' . $row, $order->id);
            $sheet->setCellValue('B' . $row, $order->order_number);
            $sheet->setCellValue('C' . $row, Carbon::parse($order->created_at)->format('d.m.Y H:i'));
            $sheet->setCellValue('D' . $row, $order->user->name ?? 'Гость');
            $sheet->setCellValue('E' . $row, number_format($order->total_amount, 0, '.', ' '));
            $sheet->setCellValue('F' . $row, $this->getStatusText($order->status));
            $sheet->setCellValue('G' . $row, $items);
            
            // Выравнивание для многострочного текста
            $sheet->getStyle('G' . $row)->getAlignment()->setWrapText(true);
            
            // Чередование цветов строк
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':G' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('f8f9fa');
            }
            
            $totalAmount += $order->total_amount;
            $row++;
        }
        
        // Применяем границы к данным
        $sheet->getStyle('A6:G' . ($row - 1))->applyFromArray($borderStyle);
        
        // Итоговая строка
        $sheet->setCellValue('D' . $row, 'ИТОГО:');
        $sheet->getStyle('D' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('E' . $row, number_format($totalAmount, 0, '.', ' '));
        $sheet->getStyle('E' . $row)->getFont()->setBold(true);
        $sheet->getStyle('E' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('ffeaa7');
        
        // Количество заказов
        $sheet->setCellValue('A' . ($row + 1), 'Всего заказов: ' . $orders->count());
        $sheet->getStyle('A' . ($row + 1))->getFont()->setBold(true);
        
        // Автоматическая высота строк для столбца с составом заказа
        foreach (range(7, $row - 1) as $r) {
            $sheet->getRowDimension($r)->setRowHeight(-1);
        }
        
        // Генерация файла
        $fileName = 'orders_report_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';
        
        $writer = new Xlsx($spreadsheet);
        
        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Экспорт наличия товаров
     */
    public function exportProducts(Request $request)
    {
        $products = Product::with(['category', 'brand'])->get();
        
        if ($products->isEmpty()) {
            return response()->json([
                'success' => false,
                'errors' => ['Нет товаров для экспорта']
            ], 404);
        }
        
        $inStock = $products->where('product_count', '>', 0);
        $outOfStock = $products->where('product_count', '<=', 0);
        
        $spreadsheet = new Spreadsheet();
        
        $spreadsheet->getProperties()
            ->setCreator('Магазин')
            ->setTitle('Отчет по остаткам товаров')
            ->setDescription('Отчет по остаткам товаров на складе');
        
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Остатки товаров');
        
        // Заголовок
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'ОТЧЕТ ПО ОСТАТКАМ ТОВАРОВ');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'Дата генерации: ' . Carbon::now()->format('d.m.Y H:i:s'));
        $sheet->getStyle('A2')->getFont()->setSize(10);
        
        // Статистика
        $sheet->setCellValue('A3', "Всего товаров: {$products->count()}");
        $sheet->setCellValue('B3', "В наличии: {$inStock->count()}");
        $sheet->setCellValue('C3', "Закончились: {$outOfStock->count()}");
        $sheet->getStyle('A3:C3')->getFont()->setBold(true);
        
        // Заголовки столбцов
        $headers = ['ID', 'Артикул', 'Название', 'Категория', 'Бренд', 'Цена (₽)', 'Остаток (шт.)', 'Статус'];
        $column = 'A';
        foreach ($headers as $index => $header) {
            $cell = $column . '5';
            $sheet->setCellValue($cell, $header);
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $sheet->getStyle($cell)->getFont()->setBold(true)->setSize(11);
            $sheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('2c3e50');
            $sheet->getStyle($cell)->getFont()->getColor()->setRGB('ffffff');
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $column++;
        }
        
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'dddddd']
                ]
            ]
        ];
        
        $row = 6;
        
        // Товары в наличии
        $sheet->setCellValue('A' . $row, '▶ ТОВАРЫ В НАЛИЧИИ');
        $sheet->mergeCells('A' . $row . ':H' . $row);
        $sheet->getStyle('A' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00b894');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->getColor()->setRGB('ffffff');
        $row++;
        
        foreach ($inStock as $product) {
            $sheet->setCellValue('A' . $row, $product->id);
            $sheet->setCellValue('B' . $row, $product->sku ?? '-');
            $sheet->setCellValue('C' . $row, $product->name);
            $sheet->setCellValue('D' . $row, $product->category->name ?? '-');
            $sheet->setCellValue('E' . $row, $product->brand->name ?? '-');
            $sheet->setCellValue('F' . $row, number_format($product->price, 0, '.', ' '));
            $sheet->setCellValue('G' . $row, $product->product_count);
            $sheet->setCellValue('H' . $row, 'В наличии');
            $sheet->getStyle('H' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('d4edda');
            $sheet->getStyle('H' . $row)->getFont()->getColor()->setRGB('155724');
            
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':H' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('f8f9fa');
            }
            $row++;
        }
        
        // Пустая строка
        $row++;
        
        // Товары которых нет
        if ($outOfStock->isNotEmpty()) {
            $sheet->setCellValue('A' . $row, '▶ ТОВАРЫ, КОТОРЫЕ ЗАКОНЧИЛИСЬ');
            $sheet->mergeCells('A' . $row . ':H' . $row);
            $sheet->getStyle('A' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('e17055');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->getColor()->setRGB('ffffff');
            $row++;
            
            foreach ($outOfStock as $product) {
                $sheet->setCellValue('A' . $row, $product->id);
                $sheet->setCellValue('B' . $row, $product->sku ?? '-');
                $sheet->setCellValue('C' . $row, $product->name);
                $sheet->setCellValue('D' . $row, $product->category->name ?? '-');
                $sheet->setCellValue('E' . $row, $product->brand->name ?? '-');
                $sheet->setCellValue('F' . $row, number_format($product->price, 0, '.', ' '));
                $sheet->setCellValue('G' . $row, $product->product_count);
                $sheet->setCellValue('H' . $row, 'Нет в наличии');
                $sheet->getStyle('H' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('f8d7da');
                $sheet->getStyle('H' . $row)->getFont()->getColor()->setRGB('721c24');
                
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':H' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('f8f9fa');
                }
                $row++;
            }
        }
        
        $sheet->getStyle('A5:H' . ($row - 1))->applyFromArray($borderStyle);
        
        $fileName = 'inventory_report_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';
        
        $writer = new Xlsx($spreadsheet);
        
        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
    
    private function getStatusText($status)
    {
        $statuses = [
            'pending' => 'Ожидает',
            'paid' => 'Оплачен',
            'processing' => 'В обработке',
            'shipped' => 'Отправлен',
            'delivered' => 'Доставлен',
            'cancelled' => 'Отменен',
        ];
        
        return $statuses[$status] ?? $status;
    }
}