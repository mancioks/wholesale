<?php

namespace App\Http\Livewire\Admin;

use App\Models\Inventorization;
use App\Models\InventorizationItem;
use App\Models\Product;
use App\Traits\LivewireProductSearch;
use Livewire\Component;

class EditInventorization extends Component
{
    use LivewireProductSearch;

    public $inventorization;

    public function mount(Inventorization $inventorization)
    {
        $this->inventorization = $inventorization;
    }

    public function rules()
    {
        return [
            'inventorization.items.*.balance' => 'required'
        ];
    }

    public function saveItem(InventorizationItem $item, $balance)
    {
        if ($balance === '') {
            $balance = null;
        }

        $item->update(['balance' => $balance]);

        $this->inventorization->refresh();
    }

    public function selectProductSearchResult(Product $product)
    {
        if ($this->inventorization->items()->where('code', $product->code)->doesntExist()) {
            InventorizationItem::query()->create([
                'name' => $product->name,
                'units' => $product->units,
                'code' => $product->code,
                'inventorization_id' => $this->inventorization->id,
            ]);
        }

        $this->inventorization->refresh();
    }

    public function deleteItem(InventorizationItem $item)
    {
        $item->delete();

        $this->inventorization->refresh();
    }

    public function render()
    {
        return view('livewire.admin.edit-inventorization');
    }
}
