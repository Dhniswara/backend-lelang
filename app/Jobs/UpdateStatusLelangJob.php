<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\LelangBarang;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateStatusLelangJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $signature = 'log:something';

    protected $description = 'Command Description';

    public function handle(): void
    {
        // Ambil semua lelang yang masih aktif dan waktunya sudah habis
        // $lelangs = LelangBarang::where('status', 'aktif')
        //     ->where('waktu_selesai', '<=', now())
        //     ->get();

        // $now = Carbon::now('Asia/Jakarta');

        // $lelangs = LelangBarang::where('status', 'aktif')
        //             ->where('waktu_selesai', '<=', $now)
        //             ->get();

        // foreach ($lelangs as $lelang) {
        //     $lelang->status = 'selesai';
        //     $lelang->save();

        // }
        // Log::info("Cron job Berhasil di jalankan " . date('Y-m-d H:i:s'));
        info(message:'Something');
    }
}
