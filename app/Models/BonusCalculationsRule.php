<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusCalculationsRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'sum',
        'min_price',
        'min_sum',
        'mid_price',
        'mid_sum',
        'max_price',
        'max_sum',
    ];
}
