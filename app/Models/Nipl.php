<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nipl extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'no_nipl',
        'no_rekening',
        'bank',
        'no_telepon',
    ];

    public function user () {
        return $this->belongsTo(User::class);
    }
}
