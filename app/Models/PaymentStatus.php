<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    protected $fillable = [
        'name',
        'key',
    ];

    public const WAITING = 1;
    public const PAID = 2;
    public const PARTLY_PAID = 3;
}
