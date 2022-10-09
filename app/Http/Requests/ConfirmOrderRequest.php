<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfirmOrderRequest extends FormRequest
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
            'payment_method' => ['required', 'exists:payment_methods,id'],
            'message' => [''],
            'name' => [Rule::when(function () { return $this->post('invoice_to_other'); }, ['required'])],
            'email' => [Rule::when(function () { return $this->post('invoice_to_other'); }, ['required'])],
            'company_name' => [Rule::when(function () { return $this->post('invoice_to_other'); }, ['required'])],
            'address' => [Rule::when(function () { return $this->post('invoice_to_other'); }, ['required'])],
            'registration_code' => [Rule::when(function () { return $this->post('invoice_to_other'); }, ['required'])],
            'vat_number' => [Rule::when(function () { return $this->post('invoice_to_other'); }, ['required'])],
            'phone_number' => [Rule::when(function () { return $this->post('invoice_to_other'); }, ['required'])],
        ];
    }
}
