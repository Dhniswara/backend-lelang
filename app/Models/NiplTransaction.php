<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NiplTransaction extends Model
{
    protected $fillable = [
        "user_id",
        "email",
        "external_id",
        "checkout_link",
        "no_telepon",
        "price",
        "status"
    ];
}
