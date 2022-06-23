<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'address',
        'registration_code',
        'vat_number',
        'phone_number',
        'get_email_notifications',
    ];
}
