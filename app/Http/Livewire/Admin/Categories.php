<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;

class Categories extends Component
{
    public $categories;
    public $categoryName;
    public $selectedCategory;
    public $masterCategories;

    public function render()
    {
        return view('livewire.admin.categories');
    }

    public function mount()
    {
        // $this->categories = Category::all();
        $this->categories = Category::query()->where('slug', 'master')->get();

        $this->masterCategories = Category::query()->where('slug', 'master')->get();
    }

    public function rules()
    {
        return [
            'categoryName' => 'required|min:3',
            'selectedCategory' => 'required|exists:categories,id',
        ];
    }

    public function add()
    {
        $this->validate();

        Category::create([
            'name' => $this->categoryName,
            'slug' => Str::slug($this->categoryName),
            'position' => Category::query()->max('position') + 1,
            'parent_id' => $this->selectedCategory,
        ]);

        // $this->categories = Category::all();
        $this->categories = Category::query()->where('slug', 'master')->get();

        $this->categoryName = '';
        $this->masterCategories = Category::query()->where('slug', 'master')->get();
    }
}
