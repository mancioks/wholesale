<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'pvm',
        'warehouse_id',
        'activated',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function cart()
    {
        return $this->belongsToMany(Product::class, 'cart')->withPivot('qty');
    }

    public function getCartCountAttribute()
    {
        $count = 0;

        foreach ($this->cart as $item) {
            $count += $item->pivot->qty;
        }

        return $count;
    }

    public function getSubTotalAttribute()
    {
        $sum = 0;

        foreach ($this->cart as $product) {
            $sum += $product->amount;
        }

        return price_format($sum);
    }

    public function getPvmSizeAttribute()
    {
        return $this->pvm ? Setting::get('pvm') : 0;
    }

    public function getGetEmailsAttribute()
    {
        if(($this->details && $this->details->get_email_notifications) || !$this->details)
            return true;
        return false;
    }

    public function getTotalAttribute()
    {
        return price_format($this->sub_total * (1 + $this->pvm_size / 100));
    }

    public function importQueue()
    {
        return $this->hasMany(ImportQueue::class, 'user_id', 'id');
    }

    public function scopeOfRole($query, ...$role_id)
    {
        return $query->whereIn('role_id', $role_id);
    }

    public function details()
    {
        return $this->hasOne(UserDetails::class);
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }
}
