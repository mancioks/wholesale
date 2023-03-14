<?php

namespace App\Http\Livewire\Admin;

use App\Models\BonusCalculation;
use App\Models\BonusCalculationsData;
use App\Models\BonusCalculationsEstimateData;
use App\Models\BonusCalculationsRule;
use App\Models\CalculatorService;
use Livewire\Component;

class BonusCalculator extends Component
{
    public $calculation;
    public $services;
    public $fact;
    public $difference;

    public $selected;
    public $edit;

    protected $rules = [
        'selected.service' => 'required|exists:calculator_services,id',
        'selected.qty' => 'required|numeric|gt:0',
        'selected.total' => 'required|numeric|min:0',
    ];

    public function mount(BonusCalculation $calculation)
    {
        $calculation->refresh();

        $this->calculation = $calculation;
        $this->services = CalculatorService::all();

        $this->selected = [];
        $this->selected['service'] = '';
        $this->selected['price'] = '';
        $this->selected['units'] = __('Quantity');

        $this->selected['edit_qty'] = '';
        $this->selected['edit_total'] = '';

        $this->edit = null;

        $this->recalculate();
    }

    public function updated()
    {
        $this->recalculate();
    }

    public function recalculate()
    {
        $this->recalculateFacts();
        $this->recalculateDifferences();
    }

    public function recalculateDifferences()
    {
        $this->difference = [];

        $this->difference['estimate'] = $this->recalculateDifference('estimate');
        $this->difference['invoice'] = $this->recalculateDifference('invoice');
    }

    public function recalculateDifference($type)
    {
        $property = sprintf('%s_total', $type);

        return $this->calculation->$property - $this->fact['max'];
    }

    public function recalculateFacts()
    {
        $this->fact = [];

        $this->fact['min'] = $this->recalculateFact('min');
        $this->fact['mid'] = $this->recalculateFact('mid');
        $this->fact['max'] = $this->recalculateFact('max');
    }

    public function recalculateFact($type)
    {
        $total = 0;
        $property = sprintf('%s_price', $type);

        /** @var BonusCalculationsEstimateData $estimateData */
        foreach ($this->calculation->estimateData as $estimateData) {
            $total += $estimateData->service->$property * $estimateData->actual_quantity;
        }

        return price_format($total);
    }

    public function updatedSelected()
    {
        $this->selected['price'] = '';
        $this->selected['units'] = __('Quantity');

        if ($this->selected['service'] !== '') {
            $service = CalculatorService::query()->find($this->selected['service']);
            $this->selected['price'] = sprintf('%s € / %s %s', $service->price, $service->step, $service->units);
            $this->selected['units'] = $service->units;
        }
    }

    public function add()
    {
        $this->validate();

        BonusCalculationsEstimateData::query()->create([
            'calculation_id' => $this->calculation->id,
            'service_id' => $this->selected['service'],
            'qty' => $this->selected['qty'],
            'actual_amount' => $this->selected['total'],
        ]);

        $this->mount($this->calculation);
    }

    public function delete($id)
    {
        $data = BonusCalculationsEstimateData::query()->find($id);

        if ($data) {
            $data->delete();
        }

        $this->mount($this->calculation);
    }

    public function edit($id)
    {
        $this->edit = $id;
        $item = BonusCalculationsEstimateData::query()->find($id);

        $this->selected['edit_qty'] = $item->qty;
        $this->selected['edit_total'] = $item->actual_amount;
    }

    public function cancelEdit()
    {
        $this->mount($this->calculation);
    }

    public function update($id)
    {
        $this->validate([
            'selected.edit_qty' => 'required|numeric|gt:0',
            'selected.edit_total' => 'required|numeric|min:0',
        ]);

        $data = BonusCalculationsEstimateData::query()->find($id);

        if ($data) {
            $data->update([
                'qty' => $this->selected['edit_qty'],
                'actual_amount' => $this->selected['edit_total'],
            ]);
        }

        $this->mount($this->calculation);
    }

    public function render()
    {
        return view('livewire.admin.bonus-calculator');
    }
}
