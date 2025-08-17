<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LelangBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'gambar_barang',
        'nama_barang',
        'deskripsi',
        'harga_awal',
        'harga_akhir',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'bid_time',
    ];
}
