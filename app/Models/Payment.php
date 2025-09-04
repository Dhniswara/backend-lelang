<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'lelang_id',
        'amount',
        'status',
        'payment_type',
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];
}
