<?php

namespace App\Http\Livewire\Admin;

use App\Traits\LivewireProductSearch;
use Livewire\Component;

class NavigationProductSearch extends Component
{
    use LivewireProductSearch;

    public function render()
    {
        return view('livewire.admin.navigation-product-search');
    }
}
