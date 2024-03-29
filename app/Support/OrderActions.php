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
            if ($order->status->id === Status::TAKEN) {
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
            $actions = [
                Status::ACCEPTED => 'Accept',
                Status::DECLINED => 'Decline',
                Status::CANCELED => 'Cancel',
                Status::PREPARING => 'Start preparing',
                Status::PREPARED => 'Order prepared',
                Status::TAKEN => 'Order taken',
                Status::DONE => 'Complete order',
                Status::CREATED => 'Restore',
            ];
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
            if ($order->status->id === Status::PREPARED) {
                $actions = [
                    Status::TAKEN => 'Order taken',
                ];
            }

            if ($order->status->id === Status::PREPARED && !$order->signature) {
                unset($actions[Status::TAKEN]);
            }
        }

        $this->actions = $actions;
    }

    public function get()
    {
        return $this->actions;
    }
}
