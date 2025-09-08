<?php

namespace App\Models;

use App\Models\LelangBarang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barang_id',
        'price',
        'checkout_link',
        'external_id',
        'status'
    ];

    public function barang(){
  return $this->belongsTo(LelangBarang::class, 'barang_id');
}
}