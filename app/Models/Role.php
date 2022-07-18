<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public const CUSTOMER = 1;
    public const WAREHOUSE = 2;
    public const ADMIN = 3;
    public const SUPER_ADMIN = 4;
}
