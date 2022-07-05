<?php

namespace App\Support;

use App\Models\Order;
use App\Models\Role;
use App\Models\Status;

class OrderListClassName
{
    public $className;

    public function __construct(Order $order)
    {
        $this->className = 'bg-secondary';

        switch (auth()->user()->role->id) {
            case Role::ADMIN:
                switch ($order->status->id) {
                    case Status::CREATED:
                        $this->className = 'bg-danger';
                        break;
                    case Status::PREPARED:
                    case Status::ACCEPTED:
                    case Status::PREPARING:
                        $this->className = 'bg-warning';
                        break;
                }
                break;
            case Role::WAREHOUSE:
                switch ($order->status->id) {
                    case Status::ACCEPTED:
                        $this->className = 'bg-danger';
                        break;
                    case Status::PREPARING:
                        $this->className = 'bg-warning';
                        break;
                }
                break;
            case Role::CUSTOMER:
                switch ($order->status->id) {
                    case Status::DECLINED:
                    case Status::CANCELED:
                        $this->className = 'bg-danger';
                        break;
                    case Status::CREATED:
                        $this->className = 'bg-primary';
                        break;
                    case Status::PREPARING:
                        $this->className = 'bg-warning';
                        break;
                    case Status::ACCEPTED:
                    case Status::PREPARED:
                        $this->className = 'bg-success';
                        break;
                }
                break;
        }
    }

    public function get()
    {
        return $this->className;
    }
}
