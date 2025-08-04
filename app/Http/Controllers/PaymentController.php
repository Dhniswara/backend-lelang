<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Midtrans\Notification;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function token(Request $request)
{
    $user = $request->user(); // asumsi pakai auth
    $orderId = 'ORDER-' . uniqid(); // bisa lebih terstruktur
    $amount = $request->amount;

    // Simpan order di DB awal sebagai pending
    $order = Order::create([
        'order_id' => $orderId,
        'user_id' => $user?->id,
        'gross_amount' => $amount,
        'status' => 'pending',
    ]);

    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $amount,
        ],
        'customer_details' => [
            'first_name' => $user?->name ?? 'Guest',
            'email' => $user?->email ?? 'unknown@example.com',
            'phone' => $user?->phone ?? '',
        ],
    ];

    $snapToken = Snap::getSnapToken($params);

    return response()->json([
        'snap_token' => $snapToken,
        'order_id' => $orderId,
    ]);
}

public function notification(Request $request)
{
    $notif = new Notification();

    $orderId = $notif->order_id;
    $transactionStatus = $notif->transaction_status;
    $fraudStatus = $notif->fraud_status;

    $order = Order::where('order_id', $orderId)->first();
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    // Simpan raw response untuk audit (update JSON)
    $order->raw_response = $notif;
    // Tentukan status baru
    if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
        if ($fraudStatus == 'accept' || $transactionStatus == 'settlement') {
            $order->status = 'paid';
        } elseif ($fraudStatus == 'challenge') {
            $order->status = 'challenge';
        } else {
            $order->status = 'fraud';
        }
    } elseif ($transactionStatus == 'pending') {
        $order->status = 'pending';
    } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
        $order->status = 'failed';
    }

    $order->save();

    // Tambahkan logic business: kirim email, update stok, dsb.
    return response()->json(['status' => 'ok']);
}


}
