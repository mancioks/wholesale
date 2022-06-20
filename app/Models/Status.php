<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['key', 'name'];

    const CREATED = 1;
    const ACCEPTED = 2;
    const DECLINED = 3;
    const CANCELED = 4;
    const PREPARING = 5;
    const PREPARED = 6;
    const DONE = 7;
}
