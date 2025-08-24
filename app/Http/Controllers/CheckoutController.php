<?php

namespace App\Http\Controllers;

use App\Models\HargaBid;
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
    public function showItem($id) {
        $barang = LelangBarang::findOrFail($id);
        return view('lelang.show', compact('barang'));
    }

    public function transactions() {
        $transactions = Transaction::with('barang')
            ->where('user_id', Auth::id())
            ->latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    public function __construct() {
        Configuration::setXenditKey(config('xendit.secret_key'));
    }

    public function payment(Request $request) {
    $barang = LelangBarang::findOrFail($request->id);

    // Ambil bid tertinggi untuk barang ini
    $highestBid = HargaBid::where('lelang_id', $barang->id)
        ->orderBy('harga', 'desc')
        ->first();

    // Cek apakah ada bid
    if (!$highestBid) {
        return response()->json([
            'message' => 'Belum ada penawaran untuk barang ini'
        ], 403);
    }

    // Cek apakah user login adalah pemenang
    if (Auth::id() !== $highestBid->user_id ) {
        return response()->json([
            'message' => 'Hanya pemenang dengan penawaran tertinggi yang dapat melakukan checkout'
        ], 403);
    }

    // Gunakan harga bid tertinggi sebagai harga invoice
    $uuid = (string) Str::uuid();
    $apiInstance = new InvoiceApi();

    $createInvoiceRequest = new CreateInvoiceRequest([
        'external_id'  => $uuid,
        'description'  => $barang->deskripsi,
        'amount'       => $highestBid->harga,
        'currency'     => 'IDR',
        "customer"     => [
            "name"  => Auth::user()->name,
            "email" => Auth::user()->email,
        ],
        "success_redirect_url" => url('/transactions'),
        "failure_redirect_url" => url('/transactions'),
    ]);

    try {
        $result = $apiInstance->createInvoice($createInvoiceRequest);

        // Simpan transaksi
        $transaction = new Transaction();
        $transaction->user_id = Auth::id();
        $transaction->price = $highestBid->harga;
        $transaction->barang_id = $barang->id;
        $transaction->checkout_link = $result['invoice_url'];
        $transaction->external_id = $uuid;
        $transaction->status = "pending";
        $transaction->save();

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

    public function notification($id) {
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
