<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard(OrderService $service)
    {
        $orders = $service->getOrders(Auth::user());

        return view('dashboard', compact('orders'));
    }
}
