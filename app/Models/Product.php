<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'units'];

    public function getAmountAttribute()
    {
        return price_format($this->price * $this->pivot->qty);
    }

    public function photo()
    {
        return $this->hasOne(Image::class, 'product_id', 'id');
    }

    public function getImageAttribute()
    {
        return $this->photo()->exists() ? $this->photo : Image::placeholder();
    }
}
