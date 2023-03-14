<?php

namespace App\Http\Livewire\Admin\Calculator;

use App\Models\CalculatorService;
use Livewire\Component;

class Services extends Component
{
    public $services;
    public $fields;

    protected $rules = [
        'fields.name' => 'required|unique:calculator_services,name',
        'fields.units' => 'required',
        'fields.step' => 'required|numeric|gt:0',
        'fields.price' => 'required|numeric|min:0',
        'fields.min_price' => 'required|numeric|min:0',
        'fields.mid_price' => 'required|numeric|min:0',
        'fields.max_price' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->services = CalculatorService::all();
        $this->fields = [];
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
        ]);

        $this->mount();
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

    public function render()
    {
        return view('livewire.admin.calculator.services');
    }
}
