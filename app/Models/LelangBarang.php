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
        'kategori_id',
        'winner_id',
        'deskripsi',
        'harga_awal',
        'harga_akhir',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'bid_time',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }
    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function bids()
    {
        return $this->hasMany(HargaBid::class, 'lelang_id');
    }
}
