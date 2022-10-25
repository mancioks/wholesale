<?php

namespace App\Http\Livewire\Admin;

use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class CreateOrder extends Component
{
    public Collection $products;
    public $productQty;
    public $searchQuery;
    public $customers;
    public $paymentMethods;
    public $warehouses;
    public $searchResults;
    public $selectedWarehouse;
    public $selectedCustomer;
    public $selectedPaymentMethod;

    public function mount()
    {
        $this->products = new Collection();
        $this->productQty = [];
        $this->searchQuery = '';
        $this->searchResults = new Collection();
        $this->customers = User::all();
        $this->paymentMethods = PaymentMethod::all();
        $this->warehouses = Warehouse::where('active', true)->get();
    }

    public function updatedSearchQuery()
    {
        if ($this->searchQuery && $this->searchQuery !== '') {
            $this->searchResults = Product::search($this->searchQuery)->take(8)->get();

            /*$this->searchResults = $this->selectedWarehouse2->products()
                ->where('name', 'LIKE', '%'.$this->searchQuery.'%')
                ->orderBy('id', 'desc')
                ->take(8)->get();*/
        } else {
            $this->searchResults = new Collection();
        }
    }

    public function add($productId)
    {
        $this->searchQuery = '';
        $this->searchResults = new Collection();

        $product = Product::query()->where('id', $productId)->first();
        if (!$this->products->find($product)) {
            $this->products->add($product);
            $this->productQty[$product->id] = 1;
        } else {
            if (array_key_exists($product->id, $this->productQty)) {
                $this->productQty[$product->id]++;
            } else {
                $this->productQty[$product->id] = 1;
            }
        }
    }

    public function remove($productId)
    {
        $this->products = $this->products->keyBy('id')->forget($productId);
    }

    public function render()
    {
        return view('livewire.admin.create-order');
    }
}
