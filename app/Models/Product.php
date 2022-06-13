<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    public function getAmountAttribute()
    {
        return number_format((float)($this->price * $this->pivot->qty), 2, '.', '');
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'product_id', 'id');
    }
}
