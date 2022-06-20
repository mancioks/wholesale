<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['name', 'value'];

    public static function get($key)
    {
        return Setting::query()->where('name', $key)->first()->value;
    }

    public static function set($key, $value, $title = false)
    {
        if(Setting::query()->where('name', $key)->exists()) {
            return Setting::query()->where('name', $key)->update([
                'value' => $value,
            ]);
        } else {
            if($title) {
                return Setting::query()->create([
                    'name' => $key,
                    'value' => $value,
                    'title' => $title,
                ]);
            }
        }

        return null;
    }

    public static function inc($key, $size = 1)
    {
        return Setting::query()->where('name', $key)->increment('value', $size);
    }
}
