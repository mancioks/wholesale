<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use App\Models\User;
use App\Traits\LivewireProductSearch;
use Livewire\Component;

class UserPrices extends Component
{
    use LivewireProductSearch;

    public $user;
    public $products = [];
    public $productsPrices = [];
    public $productsOriginalPrices = [];
    public $success = false;
    public $changed = false;

    public function mount(User $user)
    {
        $this->user = $user;

        foreach ($user->prices as $product) {
            $this->products[$product->id] = $product->toArray();
            $this->productsPrices[$product->id] = $product->pivot->price;
            $this->productsOriginalPrices[$product->id] = $product->original_price;
        }
    }

    public function updatedProductsPrices()
    {
        $this->changed = true;
    }

    public function removeProduct($id)
    {
        unset($this->products[$id]);
        unset($this->productsPrices[$id]);

        $this->success = false;
        $this->changed = true;
    }

    public function updated()
    {
        $this->success = false;
    }

    public function selectProductSearchResult(Product $product)
    {
        if (!isset($this->products[$product->id])) {
            $this->products[$product->id] = $product;
            $this->productsPrices[$product->id] = $product->original_price;
            $this->productsOriginalPrices[$product->id] = $product->original_price;

            $this->changed = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.user-prices');
    }

    public function rules()
    {
        return [
            'productsPrices.*' => 'required|numeric|min:0',
        ];
    }

    public function save()
    {
        $this->validate();

        $currentProducts = $this->user->prices->pluck('id')->toArray();
        $newProducts = array_keys($this->products);
        $productsToDelete = array_diff($currentProducts, $newProducts);
        $productsToCreate = array_diff($newProducts, $currentProducts);
        $productsToUpdate = array_intersect($currentProducts, $newProducts);

        $this->user->prices()->detach($productsToDelete);

        foreach ($productsToCreate as $productId) {
            $this->user->prices()->attach($productId, [
                'price' => $this->productsPrices[$productId],
            ]);
        }

        foreach ($productsToUpdate as $productId) {
            $this->user->prices()->updateExistingPivot($productId, [
                'price' => $this->productsPrices[$productId],
            ]);
        }

        $this->success = true;
        $this->changed = false;
    }
}
