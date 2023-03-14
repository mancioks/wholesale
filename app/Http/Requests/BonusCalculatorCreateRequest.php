<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BonusCalculatorCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'date' => 'required',
            'object' => 'required',
            'manager_id' => 'required|exists:calculator_managers,id',
            'installer_id' => 'required|exists:calculator_installers,id',
            'estimate_total' => 'required|numeric',
            'invoice_total' => 'required|numeric',
        ];
    }
}
