<?php

namespace App\Services;

use App\Models\User;

class CartService
{
    public static function clearCart(User $user)
    {
        foreach ($user->cart as $product) {
            $user->cart()->detach($product);
        }
    }
}
