<?php

namespace App\Http\Livewire\Admin;

use App\Exports\UserItemsDiscountExport;
use App\Models\DiscountRule;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class UserItemsDiscount extends Component
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

        $mappedItems = [];

        foreach ($items as $item) {
            // old key, group only by product
            // $key = $item->product_id;

            // new key, group by product and price
            $key = sprintf('%s_%s_%s', $item->product_id, $item->price, $item->order->pvm);
            $mappedItems[$key]['item'] = $item;

            if (!isset($mappedItems[$key]['quantity'])) {
                $mappedItems[$key]['quantity'] = 0;
            }

            $mappedItems[$key]['quantity'] += $item->qty;
        }

        $mappedItems = $this->applyDiscounts($mappedItems);
        $mappedItems = $this->calculateTotals($mappedItems);

        $this->items = $mappedItems;
    }

    public function exportStats()
    {
        $this->getItems();

        $export = new UserItemsDiscountExport($this->items);

        return Excel::download($export, sprintf('%s_items_discount_%s_%s.xlsx',
            $this->getFormattedUserName(),
            $this->orderType,
            $this->getRange()
        ));
    }

    private function getFormattedUserName()
    {
        $user = User::find($this->selectedUser);

        return strtolower(str_replace(' ', '_', $user->name));
    }

    private function getRange(): string
    {
        if ($this->filtering) {
            !$this->filteringFrom ? $filterFrom = 'to' : $filterFrom = $this->filteringFrom;
            !$this->filteringTo ? $filterTo = date('Y-m-d') : $filterTo = $this->filteringTo;

            return sprintf('%s_%s', $filterFrom, $filterTo);
        }

        return 'all';
    }

    private function calculateTotals(array $mappedItems): array
    {
        foreach ($mappedItems as $mappedItemKey => $mappedItem) {
            $mappedItem['amount'] = price_format($mappedItem['item']->priceWithPvm * $mappedItem['quantity']);
            $mappedItem['total'] = price_format($mappedItem['amount']);

            if (isset($mappedItem['discount'])) {
                if ($mappedItem['discount_type'] == DiscountRule::TYPE_PERCENT) {
                    $mappedItem['total'] = price_format($mappedItem['amount'] - ($mappedItem['amount'] * $mappedItem['discount_size'] / 100));
                } else {
                    $mappedItem['total'] = price_format($mappedItem['amount'] - $mappedItem['discount_size'] * $mappedItem['quantity']);
                }
            }

            $mappedItems[$mappedItemKey] = $mappedItem;
        }

        return $mappedItems;
    }

    private function applyDiscounts(array $mappedItems):array
    {
        foreach ($mappedItems as $mappedItemKey => $mappedItem) {
            $item = $mappedItem['item'];
            $quantity = $mappedItem['quantity'];

            $discounts = $item->discountRules;

            foreach ($discounts as $discount) {
                $discountFrom = $discount->from;
                $discountTo = $discount->to;

                if ($discountTo === 0) {
                    $discountTo = $quantity;
                }

                if ($quantity >= $discountFrom && $quantity <= $discountTo) {
                    $item->discount = $discount->discount;
                    $item->discount_type = $discount->discount_type;
                    $item->discounted_price = $item->discountedPrice;
                    $mappedItem['discount'] = $discount;
                    $mappedItem['discount_size'] = price_format($discount->size);
                    $mappedItem['discount_type'] = $discount->type;
                    $mappedItem['discount_model'] = $discount->model_name;
                    $mappedItems[$mappedItemKey] = $mappedItem;
                }
            }
        }

        return $mappedItems;
    }

    public function clearFilter()
    {
        $this->filteringFrom = null;
        $this->filteringTo = null;
        $this->getItems();
    }

    public function render()
    {
        return view('livewire.admin.user-items-discount');
    }
}
