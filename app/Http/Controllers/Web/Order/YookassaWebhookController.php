<?php

namespace App\Http\Controllers\Web\Order;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;

class YookassaWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $client = new Client();
        $client->setAuth(config('services.yookassa.shop_id'), config('services.yookassa.secret_key'));

        $event = $request->all();
        $paymentId = $event['object']['id'] ?? null;
        $eventType = $event['event'] ?? null;

        Log::info('Yookassa webhook', ['event' => $eventType, 'payment_id' => $paymentId]);

        if (!$paymentId) {
            return response()->json(['error' => 'No payment ID'], 400);
        }

        $order = Order::where('payment_id', $paymentId)->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        try {
            $payment = $client->getPaymentInfo($paymentId);
            $paymentStatus = $payment->getStatus();
        } catch (\Exception $e) {
            Log::error('Yookassa: Failed to get payment info', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to get payment info'], 500);
        }

        switch ($paymentStatus) {
            case 'succeeded':
                $order->update([
                    'status' => 'processing',
                    'paid_at' => now(),
                ]);

                Cart::where('user_id', $order->user_id)
                    ->whereIn('id', $request->cart_item_ids ?? [])
                    ->delete();

                Log::info("Order {$order->id} paid successfully");
                break;

            case 'canceled':
            case 'declined':
                $order->update(['status' => 'cancelled']);
                Log::info("Order {$order->id} payment failed");
                break;

            case 'pending':
                $order->update(['status' => 'pending_payment']);
                break;

            case 'waiting_for_capture':
                $order->update(['status' => 'paid']);
                break;
        }

        return response()->json(['status' => 'ok']);
    }
}
