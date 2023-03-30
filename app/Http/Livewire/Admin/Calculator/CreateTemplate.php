<?php

namespace App\Http\Livewire\Admin\Calculator;

use App\Models\CalculatorPricePeriod;
use App\Models\CalculatorTemplate;
use Livewire\Component;

class CreateTemplate extends Component
{
    public $name;
    public $pricePeriods;
    public $calculatorPricePeriodId = "";

    protected $rules = [
        'name' => 'required|string|max:255|unique:calculator_templates,name',
        'calculatorPricePeriodId' => 'required|integer|exists:calculator_price_periods,id',
    ];

    public function mount()
    {
        $this->pricePeriods = CalculatorPricePeriod::all();
    }

    public function render()
    {
        return view('livewire.admin.calculator.create-template');
    }

    public function create()
    {
        $this->validate();

        $template = CalculatorTemplate::query()->create([
            'name' => $this->name,
            'calculator_price_period_id' => $this->calculatorPricePeriodId,
        ]);

        return redirect()->route('admin.tools.bonus_calculator.templates.edit', $template);
    }
}
