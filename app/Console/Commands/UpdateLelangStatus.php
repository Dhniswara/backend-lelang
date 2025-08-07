<?php

namespace App\Console\Commands;

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
}
