<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'name', 'price', 'product_id', 'qty', 'units', 'prime_cost', 'code'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
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

    public function discountRules()
    {
        return $this->hasMany(DiscountRule::class, 'model_id', 'product_id')
            ->where('model_name', DiscountRule::MODEL_PRODUCT)
            ->where('active', true);
    }
}
