<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'name', 'price', 'product_id', 'qty'];

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
}
