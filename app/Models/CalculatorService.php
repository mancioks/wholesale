<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculatorService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'units',
        'step',
        'price',
        'min_price',
        'mid_price',
        'max_price',
        'calculator_price_period_id'
    ];

    public function calculations()
    {
        return $this->hasMany(BonusCalculationsEstimateData::class, 'service_id', 'id');
    }

    public function templates()
    {
        return $this->hasMany(CalculatorTemplateItem::class, 'service_id', 'id');
    }

    public function pricePeriod()
    {
        return $this->belongsTo(CalculatorPricePeriod::class, 'calculator_price_period_id', 'id');
    }
}
