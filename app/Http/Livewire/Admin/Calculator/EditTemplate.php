<?php

namespace App\Http\Livewire\Admin\Calculator;

use App\Models\CalculatorService;
use App\Models\CalculatorTemplate;
use App\Models\CalculatorTemplateItem;
use Livewire\Component;

class EditTemplate extends Component
{
    public $template;
    public $services;
    public $selected;

    protected $listeners = ['serviceListener'];
    protected $rules = [
        'selected.service' => 'required|exists:calculator_services,id',
        'selected.quantity' => 'required|numeric|gt:0',
    ];

    public function mount(CalculatorTemplate $template)
    {
        $template->refresh();

        $this->services = CalculatorService::all();
        $this->template = $template;
        $this->selected = [];

        $this->selected['service'] = '';
        $this->selected['quantity'] = '';
        $this->selected['units'] = __('Quantity');
    }

    public function updatedSelected()
    {
        $this->selected['units'] = __('Quantity');

        if ($this->selected['service'] !== '') {
            $service = CalculatorService::query()->find($this->selected['service']);
            $this->selected['units'] = $service->units;
        }
    }

    public function render()
    {
        $this->updatedSelected();

        return view('livewire.admin.calculator.edit-template');
    }

    public function serviceListener($serviceId)
    {
        $this->selected['service'] = $serviceId;
    }

    public function add()
    {
        $this->validate();

        CalculatorTemplateItem::query()->create([
            'template_id' => $this->template->id,
            'service_id' => $this->selected['service'],
            'quantity' => $this->selected['quantity'],
        ]);

        $this->mount($this->template);
    }

    public function delete($id)
    {
        $item = CalculatorTemplateItem::query()->find($id);

        if ($item) {
            $item->delete();
        }

        $this->mount($this->template);
    }
}
