<?php

namespace App\Http\Controllers;

use App\Models\Nipl;
use App\Models\NiplTransaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class NiplCheckoutController extends Controller
{
    public function __construct() {
        Configuration::setXenditKey(config('xendit.secret_key'));
    }

    // Buat pembayaran untuk NIPL
    public function buyNipl(Request $request) {
        $user = $request->user();

        // Cek apakah user sudah punya NIPL
        if (Nipl::where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'User sudah memiliki NIPL'
            ], 409);
        }

        $uuid = (string) Str::uuid();
        $apiInstance = new InvoiceApi();

        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id'  => $uuid,
            'description'  => 'Pembelian NIPL',
            'amount'       => 25000, 
            'currency'     => 'IDR',
            "customer"     => [
                "name"  => $user->name,
                "email" => $user->email,
                "no_telepon" => $request->no_telepon,
            ],
            "success_redirect_url" => url('/nipl/success'),
            "failure_redirect_url" => url('/nipl/failed'),
        ]);

        $result = $apiInstance->createInvoice($createInvoiceRequest);

        // Simpan transaksi
        NiplTransaction::create([
            'user_id'       => $user->id,
            'external_id'   => $uuid,
            'checkout_link' => $result['invoice_url'],
            'no_telepon'    => $request->no_telepon,
            'price'         => 25000,
            'status'        => 'pending',
        ]);

        return response()->json([
            'message' => 'Silakan lakukan pembayaran',
            'invoice_url' => $result['invoice_url'],
            'external_id' => $uuid
        ]);
    }

    // Webhook 
    public function notification(Request $request, $id) {
        $apiInstance = new InvoiceApi();
        $result = $apiInstance->getInvoices(null, $id);
        $status = strtoupper($result[0]['status']);

        // Get data
        $transaction = NiplTransaction::where('external_id', $id)->firstOrFail();

        if (! $transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $transaction->status = $result[0]['status'];
        $transaction->save();

        // Kalau sudah sukses, buatkan NIPL untuk user
        if ($status === 'PAID' || $status === 'SETTLED') {
            if (!Nipl::where('user_id', $transaction->user_id)->exists()) {
                $noNipl = str_pad(mt_rand(0, 999999), 8, '0', STR_PAD_LEFT);

                Nipl::create([
                    'user_id'     => $transaction->user_id,
                    'no_nipl'     => $noNipl,
                    'email'       => Auth::user()->email,
                    'no_telepon'  => $transaction->no_telepon,
                ]);
            }
        }

        return response()->json([
            'message' => 'Status transaksi diperbarui',
            'status' => $transaction->status
        ]);
    }
}
