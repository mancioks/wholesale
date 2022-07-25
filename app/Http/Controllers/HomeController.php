<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard(OrderService $service, Request $request)
    {
        $filters = $service->getFilters();

        $currentStatusFilterId = $request->get('filter_status_by_id');

        $currentStatusFilterName = $filters[$currentStatusFilterId] ?? "";

        $orders = $service->getOrders(Auth::user(), ['filters' => ['status' => $currentStatusFilterId]]);

        return view('dashboard', compact('orders', 'filters', 'currentStatusFilterName'));
    }
}
