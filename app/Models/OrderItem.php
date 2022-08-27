<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'name', 'price', 'product_id', 'qty', 'units', 'prime_cost'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function getAmountAttribute()
    {
        return number_format((float)($this->price * $this->qty), 2, '.', '');
    }

    public function getImageAttribute()
    {
        return $this->product()->exists() ? $this->product->image : Image::placeholder();
    }

    public function getShortageAttribute()
    {
        if ($this->stock !== null) {
            $diff = $this->stock - $this->qty;

            if($diff < 0) {
                return abs($diff);
            }
        }

        return false;
    }
}
