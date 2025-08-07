<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\UpdateLelangStatus;
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
        UpdateLelangStatus::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->job('update:lelang-status')->everyMinute();
        // $schedule->command('log:something')->everyMinute();
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
