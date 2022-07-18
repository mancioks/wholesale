<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Order $order)
    {
        return $user->id === $order->user_id || $user->role->name == 'admin' || $user->role->name == 'warehouse' || $user->role->name == 'super_admin';
    }
}
