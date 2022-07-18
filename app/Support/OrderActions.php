<?php

namespace App\Support;

use App\Models\Order;
use App\Models\Role;
use App\Models\Status;

class OrderActions
{
    public $actions;

    public function __construct(Order $order)
    {
        $actions = [];

        //admin actions
        if (auth()->user()->role->id === Role::ADMIN) {
            if ($order->status->id === Status::CREATED) {
                $actions = [
                    Status::ACCEPTED => 'Accept',
                    Status::DECLINED => 'Decline',
                ];
            }
            if ($order->status->id === Status::PREPARED) {
                $actions = [
                    Status::DONE => 'Complete order',
                ];
            }
            if ($order->status->id === Status::DECLINED) {
                $actions = [
                    Status::CREATED => 'Restore',
                ];
            }
        }

        if (auth()->user()->role->id === Role::SUPER_ADMIN) {
            if ($order->status->id === Status::CREATED) {
                $actions = [
                    Status::ACCEPTED => 'Accept',
                    Status::DECLINED => 'Decline',
                    Status::CANCELED => 'Cancel',
                ];
            }
            if ($order->status->id === Status::ACCEPTED) {
                $actions = [
                    Status::PREPARING => 'Start preparing',
                    Status::DECLINED => 'Decline',
                ];
            }
            if ($order->status->id === Status::PREPARING) {
                $actions = [
                    Status::PREPARED => 'Order prepared',
                ];
            }
            if ($order->status->id === Status::PREPARED) {
                $actions = [
                    Status::DONE => 'Complete order',
                ];
            }
            if ($order->status->id === Status::DECLINED) {
                $actions = [
                    Status::CREATED => 'Restore',
                ];
            }
        }

        //customer actions
        if (auth()->user()->role->id === Role::CUSTOMER) {
            if ($order->status->id === Status::CREATED) {
                $actions = [
                    Status::CANCELED => 'Cancel',
                ];
            }
        }

        //warehouse actions
        if (auth()->user()->role->id === Role::WAREHOUSE) {
            if ($order->status->id === Status::ACCEPTED) {
                $actions = [
                    Status::PREPARING => 'Start preparing',
                    Status::DECLINED => 'Decline',
                ];
            }
            if ($order->status->id === Status::PREPARING) {
                $actions = [
                    Status::PREPARED => 'Order prepared',
                ];
            }
        }

        $this->actions = $actions;
    }

    public function get()
    {
        return $this->actions;
    }
}
