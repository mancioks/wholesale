<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'from',
        'to',
        'size',
        'type',
        'model_name',
        'active',
        'model_id',
    ];

    const TYPE_PERCENT = 'percent';
    const TYPE_FIXED = 'fixed';

    const MODEL_PRODUCT = 'product';
    const MODEL_CATEGORY = 'category';

    const TYPES = [
        self::TYPE_PERCENT => 'Percent (%)',
        self::TYPE_FIXED => 'Fixed amount (€)',
    ];

    const MODELS = [
        self::MODEL_PRODUCT => 'Product',
        self::MODEL_CATEGORY => 'Category',
    ];

    public function model()
    {
        if ($this->model_name === self::MODEL_PRODUCT) {
            return $this->hasOne(Product::class, 'id', 'model_id');
        }

        if ($this->model_name === self::MODEL_CATEGORY) {
            return $this->hasOne(Category::class, 'id', 'model_id');
        }

        return null;
    }
}
