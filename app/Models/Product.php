<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['name', 'price', 'units'];

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

    public function getImageAttribute()
    {
        return $this->photo()->exists() ? $this->photo : Image::placeholder();
    }
}
