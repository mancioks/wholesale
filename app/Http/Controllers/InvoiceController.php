<?php

namespace App\Http\Controllers;

use App\Models\Order;

class InvoiceController extends Controller
{
    public function invoice(Order $order)
    {
        $this->authorize('view', $order);

        if (!$order->pre_invoice_required) {
            abort(404);
        }

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
