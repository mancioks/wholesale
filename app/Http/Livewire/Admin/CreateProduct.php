<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\Product;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Status;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProduct extends Component
{
    use WithFileUploads;

    public $warehouses;

    public $name;
    public $price;
    public $primeCost;
    public $units;
    public $photo;
    public $warehousePrices;
    public $warehouseEnabled;
    public $warehousesChanged;
    public $productType;
    public $productUsers;
    public $customers;
    public $selectedCustomer;

    public $productTypes = [
        Product::PRODUCT_TYPE_REGULAR => 'Regular',
        Product::PRODUCT_TYPE_PERSONALIZED => 'Personalized',
    ];

    public $success;

    public function mount()
    {
        $this->warehouses = Warehouse::all();

        $this->price = 0;
        $this->primeCost = 0;
        $this->productType = Product::PRODUCT_TYPE_REGULAR;
        $this->productUsers = new Collection();
        $this->customers = User::all();

        foreach ($this->warehouses as $warehouse) {
            $this->warehousePrices[$warehouse->id] = $this->price;
            $this->warehouseEnabled[$warehouse->id] = true;
            $this->warehousesChanged[$warehouse->id] = false;
        }
    }

    public function updatedWarehousePrices($value, $key)
    {
        if ($value != $this->price) {
            $this->warehousesChanged[$key] = true;
        }
    }

    public function updatedPrice()
    {
        foreach ($this->warehouses as $warehouse) {
            if ($this->warehousesChanged[$warehouse->id]) {
                continue;
            }
            $this->warehousePrices[$warehouse->id] = $this->price;
        }
    }

    public function addCustomer()
    {
        $customer = User::find($this->selectedCustomer);

        if (!$customer) {
            $this->addError('selectedCustomer','Customer must be selected');
            return;
        }
        $this->resetErrorBag('selectedCustomer');

        $this->productUsers->push($customer);
        $this->selectedCustomer = null;

        $this->customers = $this->customers->filter(function ($item) use ($customer) {
            return $item->id != $customer->id;
        });
    }

    public function updatedPhoto()
    {
        $validator = Validator::make(['photo' => $this->photo], [
            'photo' => 'image|max:2048|mimes:jpg,jpeg,bmp,png,webp,gif',
        ]);

        if ($validator->fails()) {
            $this->addError('photo', $validator->errors()->first('photo'));
            $this->photo = null;
        } else {
            $this->resetErrorBag('photo');
        }
    }

    public function resetWarehousePrices()
    {
        foreach ($this->warehouses as $warehouse) {
            $this->warehousePrices[$warehouse->id] = $this->price;
            $this->warehousesChanged[$warehouse->id] = false;
        }
    }

    public function submit()
    {
        if ($this->orderType === Order::ORDER_TYPE_NORMAL) {
            $this->validate([
                'selectedWarehouse' => 'required',
                'selectedCustomer' => 'required',
                'selectedPaymentMethod' => 'required',
                'products' => 'required',
            ]);
        } else {
            $this->validate([
                'selectedWarehouse' => 'required',
                'selectedCustomer' => 'required',
                'products' => 'required',
            ]);
        }

        $this->recalculateTotal();

        $this->createOrder();
        $this->resetFields();

        $this->emit('orderCreated');
        $this->success = true;
    }

    public function changePhoto()
    {
        $this->photo = null;
    }

    private function resetFields()
    {
        $this->mount();
    }

    public function render()
    {
        return view('livewire.admin.create-product');
    }
}
