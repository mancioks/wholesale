<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Order
     */
    public $title;
    public $content;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $content, $data = [])
    {
        $this->title = $title;
        $this->content = $content;
        $this->data = (object) $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->title)->view('email.custom');
    }
}
