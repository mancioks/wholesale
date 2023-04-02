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

    public $edit;

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

        $this->edit = null;
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
        $this->updatedSelectedPricePeriod($this->selectedPricePeriod);
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

    public function edit($id)
    {
        $this->edit = $id;
        $item = CalculatorService::query()->find($id);

        // clear errors
        $this->resetErrorBag();

        $this->selected['edit_name'] = $item->name;
        $this->selected['edit_units'] = $item->units;
        $this->selected['edit_step'] = $item->step;
        $this->selected['edit_price'] = $item->price;
        $this->selected['edit_min_price'] = $item->min_price;
        $this->selected['edit_mid_price'] = $item->mid_price;
        $this->selected['edit_max_price'] = $item->max_price;
    }

    public function cancelEdit()
    {
        $this->mount();
        $this->updatedSelectedPricePeriod($this->selectedPricePeriod);
    }

    public function update($id)
    {
        $this->validate([
            'selected.edit_name' => 'required',
            'selected.edit_units' => 'required',
            'selected.edit_step' => 'required|numeric|gt:0',
            'selected.edit_price' => 'required|numeric|min:0',
            'selected.edit_min_price' => 'required|numeric|min:0',
            'selected.edit_mid_price' => 'required|numeric|min:0',
            'selected.edit_max_price' => 'required|numeric|min:0',
        ]);

        $data = CalculatorService::query()->find($id);

        if ($data) {
            $data->update([
                'name' => $this->selected["edit_name"],
                'units' => $this->selected["edit_units"],
                'step' => $this->selected["edit_step"],
                'price' => $this->selected["edit_price"],
                'min_price' => $this->selected["edit_min_price"],
                'mid_price' => $this->selected["edit_mid_price"],
                'max_price' => $this->selected["edit_max_price"],
            ]);
        }

        $this->mount();
        $this->updatedSelectedPricePeriod($this->selectedPricePeriod);
    }

    public function render()
    {
        return view('livewire.admin.calculator.services');
    }
}
