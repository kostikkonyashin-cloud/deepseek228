<?php

namespace App\Http\Controllers\Web\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\Update\Order\UpdateOrderStatusByManagerRequest;
use App\Models\Order;
use Inertia\Inertia;

class ManagerController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems', 'user', 'orderItems.product'])->paginate();

        return Inertia::render('manager/IndexPage', compact(['orders']));
    }

    public function update(UpdateOrderStatusByManagerRequest $request, Order $order)
    {
        try {
            $order->update([
                'status' => $request->status
            ]);

            return back()->with('success', "Статус заказа №{$order->id} изменен на '{$request->status}'");

        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при обновлении статуса: ' . $e->getMessage());
        }
    }
}
