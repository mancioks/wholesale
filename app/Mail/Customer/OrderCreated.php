<?php

namespace App\Mail\Customer;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $response = $this->subject('Užsakymas sukurtas!')->view('email.customer.created');

        if ($this->order->pre_invoice_required) {
            $response->attachData($this->order->invoice->output(), 'invoice.pdf', [
                'mime' => 'application/pdf',
            ]);
        }

        return $response;
    }
}
