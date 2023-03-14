<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculatorInstaller extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function calculations()
    {
        return $this->hasMany(BonusCalculation::class, 'installer_id', 'id');
    }
}
