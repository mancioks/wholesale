<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculatorTemplateItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'service_id',
        'quantity',
    ];

    public function service()
    {
        return $this->hasOne(CalculatorService::class, 'id', 'service_id');
    }
}
