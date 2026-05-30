<?php

namespace App\Http\Controllers\Web\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\Store\StoreOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Illuminate\Support\Facades\DB;
use YooKassa\Client;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $cartItemIds = $request->cart_item_ids;

        $cartItems = Cart::where('user_id', auth()->id())
            ->whereIn('id', $cartItemIds)
            ->with(['product', 'color', 'size', 'material'])
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Корзина пуста');
        }

        $totalAmount = 0;
        $calculatedPrices = [];

        foreach ($cartItems as $item) {
            $basePrice = $item->product->price;
            $finalPrice = $item->product->discount
                ? $basePrice * (1 - $item->product->discount / 100)
                : $basePrice;

            $totalAmount += $finalPrice * $item->quantity;
            $calculatedPrices[$item->id] = round($finalPrice, 2);
        }
        $totalAmount = round($totalAmount, 2);

        $order = DB::transaction(function () use ($cartItems, $totalAmount, $request, $calculatedPrices, $cartItemIds) {
            $order = Order::create([
                'order_number' => date('Ymd') . '-' . strtoupper(uniqid()),
                'status' => 'processing',
                'total_amount' => $totalAmount,
                'payment_id' => null,
                'user_id' => auth()->id(),
                'address_id' => $request->address_id ?? null,
                'ordered_at' => now(),
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $calculatedPrices[$item->id],
                    'color_id' => $item->color_id,
                    'size_id' => $item->size_id,
                    'material_id' => $item->material_id,
                ]);
            }

            return $order;
        });

        $client = new Client();
        $client->setAuth(config('services.yookassa.shop_id'), config('services.yookassa.secret_key'));

        try {
            $payment = $client->createPayment([
                'amount' => ['value' => (string) $totalAmount, 'currency' => 'RUB'],
                'payment_method_data' => ['type' => 'bank_card'],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => route('webhook.yookassa'),
                ],
                'capture' => true,
                'description' => "Оплата заказа №{$order->order_number}",
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ], uniqid('', true));

            $order->update(['payment_id' => $payment->getId()]);

        } catch (Exception $e) {
            $order->update(['status' => 'cancelled']);
            return back()->with('error', 'Ошибка при создании платежа: ' . $e->getMessage());
        }

        return redirect($payment->getConfirmation()->getConfirmationUrl());
    }

    public function destroy(Order $order)
    {
        $order->load(['orderItems']);
        if ($order->user_id !== auth()->id()) {
            return back()->with('error', 'Доступ запрещен');
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Невозможно отменить заказ в текущем статусе');
        }

        DB::beginTransaction();

        try {
            foreach ($order->orderItems as $item) {
                $item->product->increment('product_count', $item->quantity);
            }

            if ($order->payment_id && $order->status !== 'cancelled') {
                $client = new Client();
                $client->setAuth(
                    config('services.yookassa.shop_id'),
                    config('services.yookassa.secret_key')
                );

                $paymentInfo = $client->getPaymentInfo($order->payment_id);

                if ($paymentInfo->getStatus() === 'succeeded' && $paymentInfo->getRefundedAmount()) {
                    $idempotenceKey = uniqid('', true);


                    $client->createRefund([
                        'payment_id' => $order->payment_id,
                        'amount' => [
                            'value' => (string) $order->total_amount,
                            'currency' => 'RUB',
                        ],
                    ], $idempotenceKey);
                }

                $order->update([
                    'status' => 'cancelled'
                ]);

                DB::commit();

                return back()->with('success', 'Заказ успешно отменен');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при отмене заказа: ' . $e->getMessage());
        }
    }
}
