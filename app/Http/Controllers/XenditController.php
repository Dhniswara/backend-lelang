<?php

namespace App\Http\Controllers;

use Xendit\Invoice\Invoice;
use Xendit\Xendit;
use Illuminate\Http\Request;

class XenditController extends Controller
{
    public function createInvoice(Request $request)
    {
        Xendit::setApiKey(config('xendit.secret_key'));

        $params = [
            'external_id' => 'order-' . time(),
            'amount' => $request->amount ?? 50000,
            'payer_email' => $request->email ?? 'test@example.com',
            'description' => $request->description ?? 'Pembelian Produk',
        ];

        $createInvoice = Invoice::create($params);

        return response()->json($createInvoice);
    }

    public function callback(Request $request)
    {
        // Callback ini dipanggil oleh Xendit setelah pembayaran selesai
        $data = $request->all();

        // Contoh update status transaksi
        // Transaction::where('external_id', $data['external_id'])
        //     ->update(['status' => $data['status']]);

        return response()->json([
            'message' => 'Callback diterima',
            'data' => $data
        ]);
    }
}
