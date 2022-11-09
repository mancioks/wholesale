<?php

namespace App\Http\Livewire\Admin;

use App\Models\Image;
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
use App\Services\WarehouseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
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

    public function removeCustomer($id)
    {
        $customer = User::find($id);

        if (!$customer) {
            return;
        }

        $this->productUsers = $this->productUsers->filter(function ($item) use ($customer) {
            return $item->id != $customer->id;
        });

        $this->customers->push($customer);
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

    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
        $this->success = false;
    }

    public function submit()
    {
        if ($this->productType === Product::PRODUCT_TYPE_REGULAR) {
            $this->validate([
                'name' => 'required|unique:products,name',
                'price' => 'required|numeric|min:0',
                'primeCost' => 'required|numeric|min:0',
                'units' => 'required',
                'warehousePrices.*' => 'required|numeric|min:0',
            ]);
        } else {
            $this->validate([
                'name' => 'required|unique:products,name',
                'price' => 'required|numeric|min:0',
                'primeCost' => 'required|numeric|min:0',
                'units' => 'required',
                'productUsers' => 'required',
                'warehousePrices.*' => 'required|numeric|min:0',
            ]);
        }

        $this->createProduct();
        $this->resetFields();

        $this->emit('productCreated');
        $this->success = true;
    }

    public function createProduct()
    {
        $product = Product::create([
            'name' => $this->name,
            'price' => $this->price,
            'prime_cost' => $this->primeCost,
            'units' => $this->units,
            'type' => $this->productType,
        ]);

        if ($this->photo) {
            $file = Storage::disk('uploads')->put('products', $this->photo);
            Image::query()->create([
                'name' => Setting::get('products.path').$file,
                'product_id' => $product->id,
            ]);
        }

        $product = WarehouseService::attachProduct($product);

        $warehouses = $product->warehouses()->get();
        foreach ($warehouses as $warehouse) {
            $price = null;
            if ($this->warehousesChanged[$warehouse->id]) {
                $price = $this->warehousePrices[$warehouse->id];
            }

            $warehouse->pivot->update(['enabled' => $this->warehouseEnabled[$warehouse->id], 'price' => $price]);
        }

        if ($this->productType === Product::PRODUCT_TYPE_PERSONALIZED) {
            $product->users()->attach($this->productUsers->pluck('id'));
        }
    }

    public function changePhoto()
    {
        $this->photo = null;
    }

    private function resetFields()
    {
        $this->mount();
        $this->name = '';
        $this->price = 0;
        $this->primeCost = 0;
        $this->units = '';
        $this->photo = null;
        $this->productType = Product::PRODUCT_TYPE_REGULAR;
        $this->productUsers = new Collection();
        $this->selectedCustomer = null;
    }

    public function render()
    {
        return view('livewire.admin.create-product');
    }
}
