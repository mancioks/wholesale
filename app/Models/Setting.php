<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['name', 'value', 'type', 'edit'];

    public static function get($key)
    {
        return Setting::query()->where('name', $key)->first()->value;
    }

    public static function set($key, $value)
    {
        if(Setting::query()->where('name', $key)->exists()) {
            return Setting::query()->where('name', $key)->update([
                'value' => $value,
            ]);
        }

        return null;
    }

    public static function inc($key, $size = 1)
    {
        return Setting::query()->where('name', $key)->increment('value', $size);
    }
}
