<?php

namespace App\Http\Livewire\Admin\Calculator;

use App\Models\CalculatorManager;
use Livewire\Component;

class Managers extends Component
{
    public $managers;
    public $fields;

    protected $rules = [
        'fields.name' => 'required|unique:calculator_managers,name'
    ];

    public function mount()
    {
        $this->managers = CalculatorManager::all();
        $this->fields = [];
    }

    public function create()
    {
        $this->validate();

        CalculatorManager::query()->create(['name' => $this->fields['name']]);
        $this->mount();
    }

    public function delete($id)
    {
        $manager = CalculatorManager::query()->find($id);

        if ($manager->calculations->count() > 0) {
            $this->addError('delete.'.$id, __('This manager has calculations'));
            return;
        }

        if ($manager) {
            $manager->delete();
        }

        $this->mount();
    }

    public function render()
    {
        return view('livewire.admin.calculator.managers');
    }
}
