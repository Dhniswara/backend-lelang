<?php

namespace App\Http\Controllers;

use App\Models\Nipl;
use Xendit\Configuration;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Xendit\Invoice\InvoiceApi;
use App\Models\NiplTransaction;
use App\Http\Controllers\Controller;
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
            "success_redirect_url" => "http://localhost:3000/home",
            "failure_redirect_url" => url('/nipl/failed'),
        ]);

        $result = $apiInstance->createInvoice($createInvoiceRequest);

        // Simpan transaksi
        NiplTransaction::create([
            'user_id'       => $user->id,
            'email'         => $user->email,
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
    
}
