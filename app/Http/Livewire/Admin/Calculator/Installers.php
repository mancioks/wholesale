<?php

namespace App\Http\Livewire\Admin\Calculator;

use App\Models\CalculatorInstaller;
use Livewire\Component;

class Installers extends Component
{
    public $installers;
    public $fields;

    protected $rules = [
        'fields.name' => 'required|unique:calculator_installers,name'
    ];

    public function mount()
    {
        $this->installers = CalculatorInstaller::all();
        $this->fields = [];
    }

    public function create()
    {
        $this->validate();

        CalculatorInstaller::query()->create(['name' => $this->fields['name']]);
        $this->mount();
    }

    public function delete($id)
    {
        $installer = CalculatorInstaller::query()->find($id);

        if ($installer->calculations->count() > 0) {
            $this->addError('delete.'.$id, __('This installer has calculations'));
            return;
        }

        if ($installer) {
            $installer->delete();
        }

        $this->mount();
    }

    public function render()
    {
        return view('livewire.admin.calculator.installers');
    }
}
