<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventorizationItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['inventorization_id', 'name', 'units', 'code', 'balance'];

    public function inventorization()
    {
        return $this->hasOne(Inventorization::class, 'id', 'inventorization_id');
    }
}
