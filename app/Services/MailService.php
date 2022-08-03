<?php

namespace App\Services;

use App\Mail\CustomMail;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public static function send($recipients, $title, $content)
    {
        foreach ($recipients as $recipient) {
            if($recipient->details->get_email_notifications) {
                Mail::to($recipient)->send(new CustomMail($title, $content));
            }
        }
    }
}
