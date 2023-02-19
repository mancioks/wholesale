<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventorization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['date', 'warehouse_id', 'user_id'];

    public function items()
    {
        return $this->hasMany(InventorizationItem::class, 'inventorization_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }
}
