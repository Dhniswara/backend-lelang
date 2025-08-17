<?php

namespace App\Http\Controllers;

use Xendit\Configuration;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\LelangBarang;
use Illuminate\Http\Request;
use Xendit\Invoice\InvoiceApi;
use Illuminate\Support\Facades\Auth;
use Xendit\Invoice\CreateInvoiceRequest;

class CheckoutController extends Controller
{
    public function showItem($id)
    {
        $barang = LelangBarang::findOrFail($id);
        return view('lelang.show', compact('barang'));
    }

    public function transactions()
    {
        $transactions = Transaction::with('barang')
            ->where('user_id', Auth::id())
            ->latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    public function __construct()
    {
        Configuration::setXenditKey(config('xendit.secret_key'));
    }

    public function payment(Request $request)
    {

        $barang = LelangBarang::find($request->id);

        $uuid = (string) Str::uuid();

        $apiInstance = new InvoiceApi();
        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id' => $uuid,
            'description' => $barang->deskripsi,
            'amount' => $barang->harga_awal,
            'currency' => 'IDR',
            "customer" => [
                "name" => "Kafka",
                "email" => "kafka@gmail.com"
            ],
            "success_redirect_url" => url('http://localhost:8000'),
            "failure_redirect_url" => url('http://localhost:8000'),
        ]);

        try {
            $result = $apiInstance->createInvoice($createInvoiceRequest);

            // Simpan transaksi
            $transactions = new Transaction();
            $transactions->user_id = 2;
            $transactions->price = $barang->harga_awal;
            $transactions->barang_id = $barang->id;
            $transactions->checkout_link = $result['invoice_url'];
            $transactions->external_id = $uuid;
            $transactions->status = "pending";
            $transactions->save();

            // Kembalikan JSON ke FE
            return response()->json([
                'invoice_url' => $result['invoice_url'],
                'external_id' => $uuid
            ]);
        } catch (\Xendit\XenditSdkException $e) {
            return response()->json([
                'message' => 'Gagal membuat invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function notification($id)
    {
        $apiInstance = new InvoiceApi();

        $result = $apiInstance->getInvoices(null, $id);

        // Get data
        $transactions = Transaction::where('external_id', $id)->firstOrFail();

        if ($transactions->status == "settled") {
            return response()->json('payment anda telah berhasil di proses');
        }

        // Update status
        $transactions->status = $result[0]['status'];
        $transactions->save();

        return response()->json('Success');
    }
}
