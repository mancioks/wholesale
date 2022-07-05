<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'amount', 'payment_method_id'];

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function method()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }
}
