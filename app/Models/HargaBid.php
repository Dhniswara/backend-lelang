<?php

namespace App\Models;

use App\Models\User;
use App\Models\LelangBarang;
use Illuminate\Database\Eloquent\Model;

class HargaBid extends Model
{
    protected $fillable = [
        'harga',
        'user_id',
        'lelang_id'
    ];


    public function lelang()
    {
        return $this->belongsTo(LelangBarang::class, 'lelang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}