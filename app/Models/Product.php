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

    public const PRODUCT_TYPE_REGULAR = 'regular';
    public const PRODUCT_TYPE_PERSONALIZED = 'personalized';

    protected $fillable = ['name', 'price', 'units', 'prime_cost', 'type', 'code', 'description', 'markup', 'additional_fees'];

    protected $originalPrice;

    public function searchableAs()
    {
        return 'name';
    }

    public function getAmountAttribute()
    {
        return price_format($this->finalPrice * $this->pivot->qty);
    }

    public function photo()
    {
        return $this->hasOne(Image::class, 'product_id', 'id');
    }

    public function priceUsers()
    {
        return $this->belongsToMany(Product::class, 'user_prices')->withPivot('price');
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_product')->withPivot(['price', 'enabled', 'markup']);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_product');
    }

    public function getImageAttribute()
    {
        return $this->photo()->exists() ? $this->photo : Image::placeholder();
    }

//    protected function price(): Attribute
//    {
//        return Attribute::make(
//            get: function ($price) {
//                if (auth()->user()) {
//                    $userIdForPrice = auth()->user()->id;
//
//                    if (auth()->user()->warehouse()->exists()) {
//                        if ($custom = $this->warehouses()->where('warehouse_id', auth()->user()->warehouse->id)->first()) {
//                            if ($custom->pivot->price) {
//                                $price = $custom->pivot->price;
//                            }
//                        }
//                    }
//
//                    if ($custom = $this->priceUsers()->where('user_id', $userIdForPrice)->first()) {
//                        $price = $custom->pivot->price;
//                    }
//                }
//
//                return $price;
//            }
//        );
//    }

    public function getUserMarkupAttribute()
    {
        $markup = $this->markup ?: 0;

        if (auth()->user()) {
            $userIdForPrice = auth()->user()->id;

            if (auth()->user()->warehouse()->exists()) {
                if ($custom = $this->warehouses()->where('warehouse_id', auth()->user()->warehouse->id)->first()) {
                    if ($custom->pivot->price) {
                        $price = $custom->pivot->price;
                    }
                }
            }

            if ($custom = $this->priceUsers()->where('user_id', $userIdForPrice)->first()) {
                $price = $custom->pivot->price;
            }
        }

        return $markup;
    }

    public function getFinalPriceAttribute()
    {
        $markup = $this->markup ?: 0;
        $price = $this->price;

        if (auth()->user() && auth()->user()->warehouse()->exists()) {
            $warehouse = $this->warehouses()->where('warehouse_id', auth()->user()->warehouse->id)->first();

            if ($warehouse->pivot->markup) {
                $markup = $warehouse->pivot->markup;
            }

            if ($warehouse->pivot->price) {
                $price = $warehouse->pivot->price;
            }
        }

        $price = price_with_markup($price, $markup);

        if (auth()->user() && auth()->user()->pvm) {
            $price = price_with_pvm($price);
        }

        $price += $this->additional_fees;

        return price_format($price);
    }

    public function warehousePrice($warehouseId = null, $pvm = false, $additionalFees = true)
    {
        $markup = $this->markup ?: 0;
        $price = $this->price;

        if ($warehouseId) {
            $warehouse = $this->warehouses()->where('warehouse_id', $warehouseId)->first();

            if ($warehouse->pivot->markup) {
                $markup = $warehouse->pivot->markup;
            }

            if ($warehouse->pivot->price) {
                $price = $warehouse->pivot->price;
            }
        }

        $price = price_with_markup($price, $markup);

        if ($pvm) {
            $price = price_with_pvm($price);
        }

        if ($additionalFees) {
            $price+= $this->additional_fees;
        }

        return price_format($price);
    }

    public function getOriginalPriceAttribute()
    {
        return $this->getRawOriginal('price');
    }
}
