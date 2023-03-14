<?php

namespace App\Http\Controllers\Admin\Tools;

use App\Http\Controllers\Controller;
use App\Models\CalculatorTemplate;
use Illuminate\Http\Request;

class BonusCalculatorTemplateController extends Controller
{
    public function index()
    {
        $templates = CalculatorTemplate::all();

        return view('admin.tools.bonus_calculator.templates', compact('templates'));
    }

    public function edit(CalculatorTemplate $bonusCalculatorTemplate)
    {
        $template = $bonusCalculatorTemplate;
        return view('admin.tools.bonus_calculator.templates.edit', compact('template'));
    }

    public function destroy(CalculatorTemplate $bonusCalculatorTemplate)
    {
        $bonusCalculatorTemplate->delete();

        return redirect()->route('admin.tools.bonus_calculator.templates');
    }
}
