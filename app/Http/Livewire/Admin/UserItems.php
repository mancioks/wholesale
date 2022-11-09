<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Component;

class UserItems extends Component
{
    public $users;
    public $filtering;
    public $filteringFrom;
    public $filteringTo;
    public $selectedUser;
    public $items;
    public $orderType;

    public $orderTypes = [
        Order::ORDER_TYPE_NORMAL => 'Normal',
        Order::ORDER_TYPE_ISSUE => 'Issue',
    ];

    public function mount()
    {
        $this->users = User::all();
        $this->filtering = false;
        $this->orderType = Order::ORDER_TYPE_NORMAL;
        $this->getItems();
    }

    public function updated()
    {
        $this->getItems();
    }

    private function getItems()
    {
        if (!$this->selectedUser) {
            $this->items = [];
            return;
        }

        $user = User::find($this->selectedUser);

        $orders = $user->orders()->whereNotIn('status_id', [Status::CANCELED, Status::DECLINED])->where('order_type', $this->orderType);
        $this->filtering = false;

        if ($this->filteringFrom || $this->filteringTo) {
            !$this->filteringFrom ? $filterFrom = '2000-01-01' : $filterFrom = $this->filteringFrom;
            !$this->filteringTo ? $filterTo = date('Y-m-d') : $filterTo = $this->filteringTo;

            if (check_date_valid($filterFrom, 'Y-m-d') && check_date_valid($filterTo, 'Y-m-d')) {
                $this->filtering = true;

                $orders = $orders->whereBetween('created_at', [
                    Carbon::createFromFormat('Y-m-d', $filterFrom)->startOfDay()->toDateTimeString(),
                    Carbon::createFromFormat('Y-m-d', $filterTo)->endOfDay()->toDateTimeString()
                ]);
            }
        }

        $ordersId = $orders->pluck('id')->toArray();

        $items = OrderItem::query()->whereIn('order_id', $ordersId)->get();
        $items = $items->groupBy(['name', 'price']);

        $this->items = $items;
    }

    public function clearFilter()
    {
        $this->filteringFrom = null;
        $this->filteringTo = null;
        $this->getItems();
    }

    public function render()
    {
        return view('livewire.admin.user-items');
    }
}
