<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['name', 'price', 'units', 'prime_cost'];

    protected $originalPrice;

    public function searchableAs()
    {
        return 'name';
    }

    public function getAmountAttribute()
    {
        return price_format($this->price * $this->pivot->qty);
    }

    public function photo()
    {
        return $this->hasOne(Image::class, 'product_id', 'id');
    }

    public function priceUsers()
    {
        return $this->belongsToMany(Product::class, 'user_prices')->withPivot('price');
    }

    public function getImageAttribute()
    {
        return $this->photo()->exists() ? $this->photo : Image::placeholder();
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: function ($price) {
                if ($custom = $this->priceUsers()->where('user_id', Auth::id())->first()) {
                    $price = $custom->pivot->price;
                }

                return $price;
            }
        );
    }

    public function getOriginalPriceAttribute()
    {
        return $this->getRawOriginal('price');
    }
}
