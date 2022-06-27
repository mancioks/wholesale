<?php

namespace App\Models;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status_id',
        'discount',
        'pvm',
        'total',
        'payment_method_id',
        'payment_status_id',
        'vat_number',
    ];

    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }

    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class);
    }

    public function getInvoiceAttribute()
    {
        return PDF::loadView('pdf.invoice', ['order' => $this]);
    }

    public function getVatInvoiceAttribute()
    {
        if($this->status->id === Status::DONE) {
            return PDF::loadView('pdf.vat-invoice', ['order' => $this]);
        }

        return null;
    }

    public function getAmountAttribute()
    {
        $amount = 0;
        foreach ($this->items as $product) {
            $amount += $product->qty * $product->price;
        }

        return price_format($amount);
    }

    public function getPvmTotalAttribute()
    {
        return price_format($this->amount * ($this->pvm / 100));
    }

    public function getTotalInWordsAttribute()
    {
        return sprintf(
            '%s EUR ir %u ct',
            ucfirst(skaicius_zodziais($this->total)),
            ($this->total * 100) % 100
        );
    }

    public function getNumberAttribute()
    {
        return sprintf('#%s', str_pad($this->id,6,"0",STR_PAD_LEFT));
    }

    public function getFullVatNumberAttribute()
    {
        return sprintf('#VND-%s', str_pad($this->vat_number,6,"0",STR_PAD_LEFT));
    }

    public function getActionsAttribute()
    {
        $actions = [];

        //admin actions
        if(auth()->user()->role->id === Role::ADMIN) {
            if($this->status->id === Status::CREATED) {
                $actions = [
                    Status::ACCEPTED => 'Accept',
                    Status::DECLINED => 'Decline',
                ];
            }
            if($this->status->id === Status::PREPARED) {
                $actions = [
                    Status::DONE => 'Complete order',
                ];
            }
            if($this->status->id === Status::DECLINED) {
                $actions = [
                    Status::CREATED => 'Restore',
                ];
            }
        }

        //customer actions
        if(auth()->user()->role->id === Role::CUSTOMER) {
            if($this->status->id === Status::CREATED) {
                $actions = [
                    Status::CANCELED => 'Cancel',
                ];
            }
        }

        //warehouse actions
        if(auth()->user()->role->id === Role::WAREHOUSE) {
            if($this->status->id === Status::ACCEPTED) {
                $actions = [
                    Status::PREPARING => 'Start preparing',
                    Status::DECLINED => 'Decline',
                ];
            }
            if($this->status->id === Status::PREPARING) {
                $actions = [
                    Status::PREPARED => 'Order prepared',
                ];
            }
        }

        return $actions;
    }
}
