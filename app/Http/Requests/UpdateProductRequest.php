<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'name' => ['required', Rule::unique('products', 'name')->ignore($this->product)],
            'price' => 'required|numeric|min:0',
            'prime_cost' => 'required|numeric|min:0',
            'image' => 'sometimes|nullable|file|mimes:jpg,jpeg,bmp,png,webp,gif',
            'units' => 'required',
        ];
    }
}
