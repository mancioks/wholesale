<?php

namespace App\Http\Livewire\Admin\Calculator;

use App\Models\CalculatorTemplate;
use Livewire\Component;

class CreateTemplate extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255|unique:calculator_templates,name',
    ];

    public function render()
    {
        return view('livewire.admin.calculator.create-template');
    }

    public function create()
    {
        $this->validate();

        $template = CalculatorTemplate::query()->create([
            'name' => $this->name,
        ]);

        return redirect()->route('admin.tools.bonus_calculator.templates.edit', $template);
    }
}
