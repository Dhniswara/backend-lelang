<?php

namespace App\Console\Commands;

use App\Models\LelangBarang;
use Illuminate\Console\Command;

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
    $nowJakarta = now('Asia/Jakarta');
    $prevMinute = $nowJakarta->copy()->subMinute();

    $updated = LelangBarang::where('status', 'aktif')
        ->whereBetween('waktu_selesai', [
            $prevMinute->toDateTimeString(),
            $nowJakarta->toDateTimeString()
        ])
        ->update(['status' => 'selesai']);

    $this->info("Status lelang yang diperbarui: $updated");
}

}
