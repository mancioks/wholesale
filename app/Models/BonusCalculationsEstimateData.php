<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusCalculationsEstimateData extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'qty',
        'actual_amount',
        'calculation_id',
    ];

    public function calculation()
    {
        return $this->hasOne(BonusCalculation::class, 'id', 'calculation_id');
    }

    public function service()
    {
        return $this->hasOne(CalculatorService::class, 'id', 'service_id');
    }

    public function getActualQuantityAttribute()
    {
        return $this->qty / $this->service->step;
    }
}
