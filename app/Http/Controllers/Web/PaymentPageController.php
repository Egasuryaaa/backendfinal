<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentPageController extends Controller
{
    /**
     * Show payment success page
     */
    public function success(Request $request)
    {
        $orderId = $request->get('order_id');

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'ID pesanan tidak ditemukan');
        }

        // Get order and payment details
        $order = Order::with(['orderItems.product', 'address', 'payments'])
                      ->find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan');
        }

        // Get latest payment for this order
        $payment = $order->payments()->latest()->first();

        // Log successful payment access
        Log::info('Payment success page accessed', [
            'order_id' => $orderId,
            'payment_status' => $payment?->status ?? 'no_payment'
        ]);

        return view('payment.success', compact('order', 'payment'));
    }

    /**
     * Show payment failed page
     */
    public function failed(Request $request)
    {
        $orderId = $request->get('order_id');

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'ID pesanan tidak ditemukan');
        }

        // Get order details
        $order = Order::with(['orderItems.product', 'address', 'payments'])
                      ->find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan');
        }

        // Get latest payment for this order
        $payment = $order->payments()->latest()->first();

        // Log failed payment access
        Log::info('Payment failed page accessed', [
            'order_id' => $orderId,
            'payment_status' => $payment?->status ?? 'no_payment'
        ]);

        return view('payment.failed', compact('order', 'payment'));
    }

    /**
     * Show payment pending page (optional)
     */
    public function pending(Request $request)
    {
        $orderId = $request->get('order_id');

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'ID pesanan tidak ditemukan');
        }

        $order = Order::with(['orderItems.product', 'address', 'payments'])
                      ->find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan');
        }

        $payment = $order->payments()->latest()->first();

        return view('payment.pending', compact('order', 'payment'));
    }
}
