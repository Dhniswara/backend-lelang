<?php

namespace App\Console;

use Carbon\Carbon;

use App\Models\LelangBarang;
use Illuminate\Support\Facades\Log;
// use App\Console\Commands\UpdateLelangStatus;
use Illuminate\Console\Scheduling\Schedule;
use App\Events\LelangUpdateStatus;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {

        // app/Console/Kernel.php (bagian schedule)
        $schedule->call(function () {
        $now = Carbon::now('Asia/Jakarta');

        $lelangs = LelangBarang::where('status', '!=', 'selesai')
            ->where('waktu_selesai', '<=', $now)
            ->get();

        foreach ($lelangs as $lelang) {
            // Ambil bid tertinggi untuk lelang ini
            $highestBid = $lelang->bids()->orderBy('harga', 'desc')->first();

            if ($highestBid) {
                $lelang->winner_id = $highestBid->user_id;
                $lelang->harga_akhir = $highestBid->harga;
                $lelang->status = 'selesai';
                $lelang->save();

                Log::info("Lelang {$lelang->id} selesai. Pemenang: {$highestBid->user_id} dengan harga: {$highestBid->harga}");
            } else {
                // Jika tidak ada bid, status tetap selesai tapi tanpa pemenang
                $lelang->status = 'selesai';
                $lelang->save();

                Log::info("Lelang {$lelang->id} selesai tanpa pemenang.");
            }

            // Trigger event untuk notifikasi
            event(new LelangUpdateStatus($lelang));
        }
    })->everyMinute();
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
