<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusCalculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'employee',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function data()
    {
        return $this->hasMany(BonusCalculationsData::class, 'calculation_id', 'id');
    }
}
