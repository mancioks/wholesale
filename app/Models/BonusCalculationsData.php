<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusCalculationsData extends Model
{
    use HasFactory;

    protected $fillable = [
        'calculation_id',
        'material',
        'price',
        'quantity',
        'used_at',
    ];

    public function rule()
    {
        return $this->hasOne(BonusCalculationsRule::class, 'id', 'bonus_calculations_rule_id');
    }
}
