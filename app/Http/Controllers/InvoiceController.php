<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function invoice(Order $order)
    {
        $this->authorize('view', $order);
        return $order->invoice->stream();
    }
}
