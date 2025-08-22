<?php

namespace App\Http\Controllers\Api;

use Xendit\Configuration;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\LelangBarang;
use Illuminate\Http\Request;
use Xendit\Invoice\InvoiceApi;
use Xendit\XenditSdkException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Xendit\Invoice\CreateInvoiceRequest;

class ApiCheckoutController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(config('xendit.secret_key'));
    }

    // Detail barang
    public function showItem($id)
    {
        $barang = LelangBarang::find($id);

        if (!$barang) {
            return response()->json([
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'data' => $barang
        ]);
    }

    // Daftar transaksi user login
    public function transactions()
    {
        $transactions = Transaction::with('barang')
            ->where('user_id', Auth::id())
            ->latest()->get();

        return response()->json([
            'data' => $transactions
        ]);
    }

    // Buat pembayaran
    public function payment(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:lelang_barangs,id'
        ]);

        $barang = LelangBarang::find($request->id);

        $uuid = (string) Str::uuid();

        $apiInstance = new InvoiceApi();

        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id'  => $uuid,
            'description'  => $barang->deskripsi,
            'amount'       => $barang->harga_awal,
            'currency'     => 'IDR',
            "customer"     => [
                "name" => Auth::user()->name,
                "email" => Auth::user()->email
            ],
            "success_redirect_url" => url('/api/payment/success'),
            "failure_redirect_url" => url('/api/payment/failed'),
        ]);

        try {
            $result = $apiInstance->createInvoice($createInvoiceRequest);

            $transactions = new Transaction();
            $transactions->user_id = Auth::id();
            $transactions->price = $barang->harga_awal;
            $transactions->barang_id = $barang->id;
            $transactions->checkout_link = $result['invoice_url'];
            $transactions->external_id = $uuid;
            $transactions->status = "pending";
            $transactions->save();

            return response()->json([
                'message' => 'Invoice berhasil dibuat',
                'invoice_url' => $result['invoice_url'],
                'external_id' => $uuid
            ]);
        } catch (XenditSdkException $e) {
            return response()->json([
                'message' => 'Gagal membuat invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Webhook xendit
    public function notification($id)
    {
        $apiInstance = new InvoiceApi();

        $result = $apiInstance->getInvoices(null, $id);

        $transaction = Transaction::where('external_id', $id)->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        if ($transaction->status == "settled") {
            return response()->json([
                'message' => 'Pembayaran sudah berhasil diproses'
            ]);
        }

        $transaction->status = $result[0]['status'];
        $transaction->save();

        return response()->json([
            'message' => 'Status transaksi berhasil diperbarui',
            'status'  => $transaction->status
        ]);
    }
}
