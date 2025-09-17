<?php

namespace App\Http\Controllers;

use App\Models\Nipl;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\NiplTransaction;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
     public function handle(Request $request)
    {
        $data = $request->all();

        // Pastikan payload valid
        if (!isset($data['external_id']) || !isset($data['status'])) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $externalId = $data['external_id'];
        $status     = strtoupper($data['status']);

        // Cek apakah transaksi NIPL
        $niplTransaction = NiplTransaction::where('external_id', $externalId)->first();
        if ($niplTransaction) {
            return $this->handleNipl($niplTransaction, $status);
        }

        // Cek apakah transaksi Barang
        $transaction = Transaction::where('external_id', $externalId)->first();
        if ($transaction) {
            return $this->handleBarang($transaction, $status, $externalId);
        }

        return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
    }

    private function handleNipl($transaction, $status)
    {
        // Update status transaksi
        $transaction->status = $status;
        $transaction->save();

        // Kalau status sukses, buatkan NIPL
        if (in_array($status, ['PAID', 'SETTLED'])) {
            Log::info("Webhook PAID/SETTLED untuk transaksi {$transaction->id}, user {$transaction->user_id}");

            if (!Nipl::where('user_id', $transaction->user_id)->exists()) {
                $noNipl = str_pad(mt_rand(0, 999999), 8, '0', STR_PAD_LEFT);

                Nipl::create([
                    'user_id'     => $transaction->user_id,
                    'no_nipl'     => $noNipl,
                    'email'       => $transaction->email,
                    'no_telepon'  => $transaction->no_telepon,
                ]);
            }
        }

        return response()->json([
            'message' => 'NIPL transaction updated',
            'status'  => $transaction->status
        ]);
    }

    private function handleBarang($transaction, $status)
    {
        // Update status
        $transaction->status = $status;
        $transaction->save();

        Log::info("Webhook BARANG untuk transaksi {$transaction->id}, status: {$transaction->status}");

        return response()->json([
            'message' => 'Barang transaction updated',
            'status'  => $transaction->status
        ]);
    }

}
