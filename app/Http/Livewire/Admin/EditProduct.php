<?php

namespace App\Http\Livewire\Admin;

use App\Models\Image;
use App\Models\Product;
use App\Models\Setting;
use App\Services\WarehouseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProduct extends Component
{
    use WithFileUploads;

    public $product;
    public $productTypes = [
        Product::PRODUCT_TYPE_REGULAR => 'Regular',
        Product::PRODUCT_TYPE_PERSONALIZED => 'Personalized',
    ];
    public $success;
    public $image;
    public $photo;
    public $warehouses;

    public $warehousePrices;
    public $warehouseMarkups;
    public $warehouseEnabled;
    public $warehousesChanged;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->image = $product->image->name;
        $this->success = false;
        $this->warehouses = $product->warehouses;

        foreach ($product->warehouses as $warehouse) {
            $this->warehousePrices[$warehouse->id] = $warehouse->pivot->price ?: $product->price;
            $this->warehouseMarkups[$warehouse->id] = $warehouse->pivot->markup ?: $product->markup;
            $this->warehouseEnabled[$warehouse->id] = $warehouse->pivot->enabled ?: false;
            $this->warehousesChanged[$warehouse->id] = (bool)$warehouse->pivot->price;
        }
    }

    public function updated()
    {
        $this->success = false;
    }

    public function render()
    {
        return view('livewire.admin.edit-product');
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

    public function changeOriginalPhoto()
    {
        $this->image = null;
    }

    public function resetOriginalPhoto()
    {
        $this->image = $this->product->image->name;
        $this->photo = null;
    }

    public function changePhoto()
    {
        $this->photo = null;
    }

    public function rules()
    {
        return [
            'product.name' => 'required',
            'product.code' => 'required|unique:products,code,' . $this->product->id,
            'product.price' => 'required|numeric|min:0',
            'product.prime_cost' => 'required|numeric|min:0',
            'product.additional_fees' => 'required|numeric|min:0',
            'product.markup' => 'required|numeric',
            'product.units' => 'required',
            'product.type' => 'required|in:' . implode(',', array_keys($this->productTypes)),
            'product.description' => '',
            'warehousePrices.*' => 'required|numeric|min:0',
            'warehouseMarkups.*' => 'required|numeric',
        ];
    }

    public function save()
    {
        $this->validate();

        if ($this->image === null && $this->product->photo) {
            $this->product->photo->delete();
        }

        if ($this->photo) {
            $file = Storage::disk('uploads')->put('products', $this->photo);
            Image::query()->create([
                'name' => Setting::get('products.path').$file,
                'product_id' => $this->product->id,
            ]);
        }

        $this->product->save();

        $product = WarehouseService::attachProduct($this->product);

        $warehouses = $product->warehouses()->get();
        foreach ($warehouses as $warehouse) {
            $price = null;
            $markup = null;

            if ($this->warehousesChanged[$warehouse->id]) {
                $price = $this->warehousePrices[$warehouse->id];
                $markup = $this->warehouseMarkups[$warehouse->id];
            }

            $warehouse->pivot->update(['enabled' => $this->warehouseEnabled[$warehouse->id], 'price' => $price, 'markup' => $markup]);
        }

        $this->product->refresh();
        $this->resetOriginalPhoto();

        $this->success = true;
    }

    public function resetWarehousePrices()
    {
        foreach ($this->warehouses as $warehouse) {
            $this->warehousePrices[$warehouse->id] = $this->product->price;
            $this->warehouseMarkups[$warehouse->id] = $this->product->markup;
            $this->warehousesChanged[$warehouse->id] = false;
        }
    }

    public function updatedWarehousePrices($value, $key)
    {
        if ($value != $this->product->price) {
            $this->warehousesChanged[$key] = true;
        }
    }

    public function updatedWarehouseMarkups($value, $key)
    {
        if ($value != $this->product->markup) {
            $this->warehousesChanged[$key] = true;
        }
    }

    public function updatedProductPrice()
    {
        foreach ($this->warehouses as $warehouse) {
            if ($this->warehousesChanged[$warehouse->id]) {
                continue;
            }
            $this->warehousePrices[$warehouse->id] = $this->product->price;
        }
    }

    public function updatedProductMarkup()
    {
        foreach ($this->warehouses as $warehouse) {
            if ($this->warehousesChanged[$warehouse->id]) {
                continue;
            }
            $this->warehouseMarkups[$warehouse->id] = $this->product->markup;
        }
    }
}
