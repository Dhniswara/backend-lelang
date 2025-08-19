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
    // /**
    //  * Define the application's command schedule.
    //  *
    //  * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
    //  * @return void
    //  */
    protected $commands = [
        
    ];

    protected function schedule(Schedule $schedule)
    {
                
            // app/Console/Kernel.php (bagian schedule)
        $schedule->call(function () {
            $now = Carbon::now('Asia/Jakarta');

            $lelangs = LelangBarang::where('status', '!=', 'selesai')
                        ->where('waktu_selesai', '<=', $now)
                        ->get();

            foreach ($lelangs as $lelang) {
                $lelang->status = 'selesai';
                $lelang->save();

                Log::info("Dispatching LelangSelesaiEvent for id: {$lelang->id}");
                event(new LelangUpdateStatus($lelang)); // kirim model, bukan $lelang->id
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
