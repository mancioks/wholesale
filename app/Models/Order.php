<?php

namespace App\Models;

use App\Services\OrderService;
use App\Support\OrderActions;
use App\Support\OrderListClassName;
use Barryvdh\DomPDF\Facade\Pdf;
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
        'warehouse_id',
        'message',
        'company_details',
        'customer_name',
        'customer_email',
        'customer_company_name',
        'customer_company_address',
        'customer_company_registration_code',
        'customer_company_vat_number',
        'customer_company_phone_number',
        'created_by',
        'pre_invoice_required',
        'order_type',
        'waybill_required',
    ];

    public const ORDER_TYPE_NORMAL = 'normal';
    public const ORDER_TYPE_ISSUE = 'issue';

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

    public static function boot() {
        parent::boot();

        static::deleting(function($order) {
            if($order->payments) {
                foreach ($order->payments as $payment) {
                    $payment->delete();
                }
            }
            if($order->items) {
                foreach ($order->items as $item) {
                    $item->delete();
                }
            }
        });
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }

    public function paymentStatus()
    {
        return $this->hasOne(PaymentStatus::class, 'id', 'payment_status_id');
    }

    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }

    public function getPaidTotalAttribute()
    {
        $total = 0;
        foreach ($this->payments as $payment) {
            $total += $payment->amount;
        }

        return $total;
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function getInvoiceAttribute()
    {
        return PDF::loadView('pdf.invoice', ['order' => $this]);
    }

    public function getVatInvoiceAttribute()
    {
//        if ($this->vat_number > 0) {
//            return PDF::loadView('pdf.vat-invoice', ['order' => $this]);
//        }

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
            ucfirst(amountToLtWords($this->total)),
            ($this->total * 100) % 100
        );
    }

    public function getNumberAttribute()
    {
        return sprintf('#%s', str_pad($this->id, 6, "0", STR_PAD_LEFT));
    }

    public function getFullVatNumberAttribute()
    {
        return sprintf('#VND-%s', str_pad($this->vat_number, 6, "0", STR_PAD_LEFT));
    }

    public function getIsCanceledAttribute()
    {
        if ($this->status->id === Status::CANCELED || $this->status->id === Status::DECLINED)
            return true;
        return false;
    }

    public function getListClassAttribute()
    {
        return (new OrderListClassName($this))->get();
    }

    public function getActionsAttribute()
    {
        return (new OrderActions($this))->get();
    }

    public function createdByUser()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
