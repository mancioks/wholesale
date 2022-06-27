<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Status;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function invoice(Order $order)
    {
        $this->authorize('view', $order);
        return $order->invoice->stream();
    }

    public function vatInvoice(Order $order)
    {
        $this->authorize('view', $order);

        if($order->vat_invoice) {
            return $order->vat_invoice->stream();
        }

        return abort(403);
    }
}
