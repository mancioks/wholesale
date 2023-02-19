<?php

namespace App\Http\Controllers\Admin\Tools;

use App\Http\Controllers\Controller;
use App\Http\Requests\BonusCalculatorDataImportRequest;
use App\Http\Requests\CreateBonusCalculatorRuleRequest;
use App\Imports\BonusCalculatorDataImport;
use App\Imports\ProductsImport;
use App\Models\BonusCalculation;
use App\Models\BonusCalculationsData;
use App\Models\BonusCalculationsRule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BonusCalculator extends Controller
{
    public function index()
    {
        $calculations = BonusCalculation::query()->orderByDesc('id')->get();

        return view('admin.tools.bonus_calculator', compact('calculations'));
    }

    public function create()
    {
        return view('admin.tools.bonus_calculator.create');
    }

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
