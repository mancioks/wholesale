<?php

namespace App\Http\Controllers\Admin\Tools;

use App\Http\Controllers\Controller;
use App\Http\Requests\BonusCalculatorCreateRequest;
use App\Http\Requests\BonusCalculatorDataImportRequest;
use App\Http\Requests\CreateBonusCalculatorRuleRequest;
use App\Imports\BonusCalculatorDataImport;
use App\Imports\ProductsImport;
use App\Models\BonusCalculation;
use App\Models\BonusCalculationsData;
use App\Models\BonusCalculationsEstimateData;
use App\Models\BonusCalculationsRule;
use App\Models\CalculatorInstaller;
use App\Models\CalculatorManager;
use App\Models\CalculatorPricePeriod;
use App\Models\CalculatorTemplate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BonusCalculator extends Controller
{
    public function index()
    {
        $calculations = BonusCalculation::query()->orderByDesc('id')->get();

        $managers = CalculatorManager::all();
        $installers = CalculatorInstaller::all();

        return view('admin.tools.bonus_calculator', compact('calculations', 'managers', 'installers'));
    }

    public function create()
    {
        $installers = CalculatorInstaller::all();
        $managers = CalculatorManager::all();
        $templates = CalculatorTemplate::all();
        $pricePeriods = CalculatorPricePeriod::all();

        return view('admin.tools.bonus_calculator.create', compact('installers', 'managers', 'templates', 'pricePeriods'));
    }

    public function submit(BonusCalculatorCreateRequest $request)
    {
        $calculation = BonusCalculation::query()->create($request->validated() + ['user_id' => auth()->user()->id]);

        if ($request->pricer_id && $request->pricer_id !== '') {
            $calculation->update(['calculator_price_period_id' => $request->pricer_id]);
        }

        if ($request->template_id && $request->template_id !== '') {
            $template = CalculatorTemplate::query()->findOrFail($request->template_id);
            $calculation->update(['calculator_price_period_id' => $template->calculator_price_period_id]);
            foreach ($template->items as $item) {
                BonusCalculationsEstimateData::query()->create([
                    'calculation_id' => $calculation->id,
                    'service_id' => $item->service_id,
                    'qty' => $item->quantity,
                    'actual_amount' => 0,
                ]);
            }
        }

        return redirect()->route('admin.tools.bonus_calculator.show', $calculation);
    }

    /**
     * @deprecated
     */
    public function import(BonusCalculatorDataImportRequest $request)
    {
        $report = $request->file('report');
        Excel::import(new BonusCalculatorDataImport, $report);

        $bonusCalculation = BonusCalculation::query()->create([
            'name' => $report->getClientOriginalName(),
            'employee' => $request->employee,
            'user_id' => auth()->user()->id,
        ]);

        BonusCalculationsData::query()->where('calculation_id', 0)->update([
            'calculation_id' => $bonusCalculation->id,
        ]);

        return redirect()->route('admin.tools.bonus_calculator.show', $bonusCalculation);
    }

    public function show(BonusCalculation $bonusCalculation)
    {
        return view('admin.tools.bonus_calculator.show', ['calculation' => $bonusCalculation]);
    }

    public function rules()
    {
        $rules = BonusCalculationsRule::all();

        return view('admin.tools.bonus_calculator.rules', ['rules' => $rules]);
    }

    public function createRule(CreateBonusCalculatorRuleRequest $request)
    {
        BonusCalculationsRule::query()->create($request->validated());

        return redirect()->back()->with('status', 'Success');
    }
}
