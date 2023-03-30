<?php

namespace App\Http\Livewire\Admin\Calculator;

use App\Models\CalculatorPricePeriod;
use App\Models\CalculatorService;
use Livewire\Component;

class Services extends Component
{
    public $services;
    public $fields;
    public $pricePeriods;
    public $selected;
    public $selectedPricePeriod = "";
    public $selectedPricePeriodToCopy = "";

    protected $rules = [
        'fields.name' => 'required',
        'fields.units' => 'required',
        'fields.step' => 'required|numeric|gt:0',
        'fields.price' => 'required|numeric|min:0',
        'fields.min_price' => 'required|numeric|min:0',
        'fields.mid_price' => 'required|numeric|min:0',
        'fields.max_price' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->services = CalculatorService::query()->where('id', 0)->get();
        $this->pricePeriods = CalculatorPricePeriod::all();
        $this->fields = [];
        $this->selected = [];
    }

    public function create()
    {
        $this->validate();

        CalculatorService::query()->create([
            'name' => $this->fields['name'],
            'units' => $this->fields['units'],
            'step' => $this->fields['step'],
            'price' => $this->fields['price'],
            'min_price' => $this->fields['min_price'],
            'mid_price' => $this->fields['mid_price'],
            'max_price' => $this->fields['max_price'],
            'calculator_price_period_id' => (int)$this->selectedPricePeriod,
        ]);

        $this->mount();
        $this->updatedSelectedPricePeriod($this->selectedPricePeriod);
    }

    public function createPeriod()
    {
        $this->validate([
            'selected.date_from' => 'required|date',
            'selected.date_to' => 'required|date|after:selected.date_from',
            'selected.period_name' => 'required|unique:calculator_price_periods,name',
        ]);

        $pricePeriod = CalculatorPricePeriod::query()->create([
            'name' => $this->selected['period_name'],
            'from' => $this->selected['date_from'],
            'to' => $this->selected['date_to'],
        ]);

        $this->mount();

        $this->selectedPricePeriod = $pricePeriod->id;
        $this->updatedSelectedPricePeriod($pricePeriod->id);
    }

    public function updatedSelectedPricePeriod($value)
    {
        $pricePeriod = CalculatorPricePeriod::query()->find($value);

        if ($pricePeriod) {
            $this->services = $pricePeriod->services;
        } else {
            // $this->services make empty collection
            $this->services = CalculatorService::query()->where('id', 0)->get();
        }

        $this->selectedPricePeriodToCopy = "";
    }

    public function delete($id)
    {
        $service = CalculatorService::query()->find($id);

        if ($service->calculations->count() > 0 || $service->templates->count() > 0) {
            if ($service->calculations->count() > 0) {
                $this->addError('delete.'.$id, __('This service has calculations'));
            }

            if ($service->templates->count() > 0) {
                $this->addError('delete.'.$id, __('This service has templates'));
            }

            return;
        }

        if ($service) {
            $service->delete();
        }

        $this->mount();
    }

    public function copyServices()
    {
        $pricePeriod = CalculatorPricePeriod::query()->find($this->selectedPricePeriod);
        $pricePeriodToCopy = CalculatorPricePeriod::query()->find($this->selectedPricePeriodToCopy);

        if ($pricePeriod && $pricePeriodToCopy) {
            foreach ($pricePeriodToCopy->services as $service) {
                $pricePeriod->services()->create([
                    'name' => $service->name,
                    'units' => $service->units,
                    'step' => $service->step,
                    'price' => $service->price,
                    'min_price' => $service->min_price,
                    'mid_price' => $service->mid_price,
                    'max_price' => $service->max_price,
                ]);
            }

            $this->mount();
            $this->updatedSelectedPricePeriod($this->selectedPricePeriod);
        }
    }

    public function render()
    {
        return view('livewire.admin.calculator.services');
    }
}
