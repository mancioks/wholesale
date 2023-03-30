<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculatorPricePeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'from',
        'to'
    ];

    public function services()
    {
        return $this->hasMany(CalculatorService::class, 'calculator_price_period_id', 'id');
    }
}
