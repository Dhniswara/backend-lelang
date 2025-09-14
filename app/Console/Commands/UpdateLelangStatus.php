<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateStatusLelangJob;


class UpdateLelangStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'barang:update-status-barang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status lelang otomatis jika kolom waktu_selesai sudah lewat';

    /**
     * Execute the console command.
     */
    public function handle()
{
     UpdateStatusLelangJob::dispatch();
}

}
