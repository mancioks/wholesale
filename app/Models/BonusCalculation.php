<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusCalculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'object',
        'manager_id',
        'installer_id',
        'estimate_total',
        'invoice_total',
        'calculator_price_period_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function manager()
    {
        return $this->hasOne(CalculatorManager::class, 'id', 'manager_id');
    }

    public function installer()
    {
        return $this->hasOne(CalculatorInstaller::class, 'id', 'installer_id');
    }

    public function estimateData()
    {
        return $this->hasMany(BonusCalculationsEstimateData::class, 'calculation_id', 'id');
    }

    public function pricePeriod()
    {
        return $this->hasOne(CalculatorPricePeriod::class, 'id', 'calculator_price_period_id');
    }
}
