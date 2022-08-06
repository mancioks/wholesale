<?php

namespace App\Mail\Customer;

use App\Mail\Admin\OrderCreated;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderDone extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Order
     */
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
        return $this->subject('Užsakymas užbaigtas!')
            ->view('email.customer.done')
            /*->attachData($this->order->vat_invoice->output(), 'invoice.pdf', [
            'mime' => 'application/pdf',
        ])*/;
    }
}
