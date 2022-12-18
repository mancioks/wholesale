<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\DiscountRule;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class CreateDiscountRule extends Component
{
    public $types;
    public $models;
    public $discountRule;
    public $searchQuery;
    public $searchResults;
    public $selectedModel;
    public $categories;
    public $success;

    public function mount()
    {
        $this->discountRule = new DiscountRule();
        $this->types = DiscountRule::TYPES;
        $this->models = DiscountRule::MODELS;
        $this->searchQuery = '';
        $this->searchResults = new Collection();
        $this->categories = Category::all();
        $this->success = false;
    }

    public function rules()
    {
        $rules = [
            'discountRule.type' => 'required|in:' . implode(',', array_keys($this->types)),
            'discountRule.model_name' => 'required|in:' . implode(',', array_keys($this->models)),
            'discountRule.from' => 'required|numeric|min:0',
            'discountRule.to' => 'required|numeric|min:0',
            'discountRule.size' => 'required|numeric|min:0',
            'discountRule.model_id' => 'required|numeric',
        ];

        if ($this->discountRule->type === DiscountRule::TYPE_PERCENT) {
            $rules['discountRule.size'] = 'required|numeric|min:0|max:100';
        }

        if ($this->discountRule->model_name === DiscountRule::MODEL_CATEGORY) {
            $rules['discountRule.model_id'] = 'required|numeric|exists:categories,id';
        }

        if ($this->discountRule->model_name === DiscountRule::MODEL_PRODUCT) {
            $rules['discountRule.model_id'] = 'required|numeric|exists:products,id';
        }

        return $rules;
    }

    public function updated()
    {
        $this->success = false;
    }

    public function updatedSearchQuery()
    {
        if ($this->searchQuery && $this->searchQuery !== '') {
            $this->searchResults = Product::search($this->searchQuery)->take(8)->get();
        } else {
            $this->searchResults = new Collection();
        }
    }

    public function selectProduct($productId)
    {
        $this->searchQuery = '';
        $this->searchResults = new Collection();

        $product = Product::find($productId);
        $this->selectedModel = $product;
        $this->discountRule->model_id = $product->id;
    }

    public function render()
    {
        return view('livewire.admin.create-discount-rule');
    }

    public function updatedDiscountRuleModelName()
    {
        $this->discountRule->model_id = null;
        $this->selectedModel = null;
    }

    public function updatedDiscountRuleModelId()
    {
        $model = null;

        if ($this->discountRule->model_name === DiscountRule::MODEL_PRODUCT) {
            $model = Product::find($this->discountRule->model_id);
        }

        if ($this->discountRule->model_name === DiscountRule::MODEL_CATEGORY) {
            $model = Category::find($this->discountRule->model_id);
        }

        $this->selectedModel = $model;
    }

    public function changeSelectedModel()
    {
        $this->discountRule->model_id = null;
        $this->selectedModel = null;
    }

    public function resetFields()
    {
        $this->mount();
        $this->discountRule = new DiscountRule();
        $this->searchQuery = '';
        $this->searchResults = new Collection();
        $this->selectedModel = null;
    }

    public function submit()
    {
        $this->validate();
        $this->discountRule->save();
        $this->emit('discountRuleCreated');

        $this->resetFields();

        $this->success = true;
    }
}
