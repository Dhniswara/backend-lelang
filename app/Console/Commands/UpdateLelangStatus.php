<?php

namespace App\Console\Commands;

<<<<<<< HEAD
use Illuminate\Console\Command;
use App\Models\LelangBarang;
use Carbon\Carbon;

class UpdateLelangStatus extends Command
{
    protected $signature = 'lelang:update-status';
    protected $description = 'Update status lelang yang sudah selesai';


    public function handle()
    {
        // $now = Carbon::now('Asia/Jakarta');

        // $lelangs = LelangBarang::where('status', 'aktif')
        //             ->where('waktu_selesai', '<=', $now)
        //             ->get();

        $lelangs = LelangBarang::where('status', 'aktif')
        ->where('waktu', '<=', Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'))
        ->get();

        foreach ($lelangs as $lelang) {
            $lelang->status = 'selesai';
            $lelang->save();
        }

        $this->info('Status lelang diperbarui.');
    }
=======
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

>>>>>>> 1f485e177a6ba6981b1dd48a6f2a798e8aa040f5
}
