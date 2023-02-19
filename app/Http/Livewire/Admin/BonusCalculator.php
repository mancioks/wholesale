<?php

namespace App\Http\Livewire\Admin;

use App\Models\BonusCalculation;
use App\Models\BonusCalculationsData;
use App\Models\BonusCalculationsRule;
use Livewire\Component;

class BonusCalculator extends Component
{
    public $calculation;
    public $calculationRules;
    public $calculationDataRules;

    public function mount(BonusCalculation $calculation)
    {
        $this->calculation = $calculation;
        $this->calculationRules = BonusCalculationsRule::all();
        $this->calculationDataRules = $this->getCalculationDataRules();
    }

    private function getCalculationDataRules()
    {
        $rules = [];

        foreach ($this->calculation->data as $calculationData) {
            $rules[$calculationData->id] = $calculationData->bonus_calculations_rule_id;
        }

        return $rules;
    }

    public function updatedCalculationDataRules()
    {
        foreach ($this->calculationDataRules as $id => $ruleId) {
            if ($ruleId === '') {
                $ruleId = null;
            }

            $this->calculation->data()->where(['id' => $id])->update(['bonus_calculations_rule_id' => $ruleId]);
        }

        $this->calculation->refresh();
    }

    public function delete(BonusCalculationsData $bonusCalculationsData)
    {
        $bonusCalculationsData->delete();
        $this->calculation->refresh();
    }

    public function render()
    {
        return view('livewire.admin.bonus-calculator');
    }
}
