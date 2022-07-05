<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportQueue extends Model
{
    protected $fillable = ['name', 'price', 'units', 'user_id'];
}
