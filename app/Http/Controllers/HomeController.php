<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if($user->role->name == 'customer') {
            $orders = $user->orders;
        } elseif ($user->role->name == 'warehouse') {
            $orders = Order::all();
        } else {
            $orders = Order::all();
        }

        return view('dashboard', compact('orders'));
    }
}
