<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculatorManager extends Model
{
    use HasFactory;

    public $fillable = ['name'];

    public function calculations()
    {
        return $this->hasMany(BonusCalculation::class, 'manager_id', 'id');
    }
}
