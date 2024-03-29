<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculatorTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'calculator_price_period_id',
    ];

    public function items()
    {
        return $this->hasMany(CalculatorTemplateItem::class, 'template_id', 'id');
    }
}
