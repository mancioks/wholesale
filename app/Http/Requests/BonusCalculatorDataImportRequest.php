<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BonusCalculatorDataImportRequest extends FormRequest
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
            'report' => 'required|file|mimes:csv,xlsx',
            'employee' => 'required',
        ];
    }
}
