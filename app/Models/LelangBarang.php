<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LelangBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'deskripsi',
        'harga_awal',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'bid_time',
    ];
}
