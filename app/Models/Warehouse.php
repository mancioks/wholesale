<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['name', 'email', 'address', 'phone_number', 'active'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'warehouse_product')->withPivot(['price', 'enabled']);
    }
}
