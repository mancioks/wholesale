<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|unique:products,name',
            'price' => 'required|numeric|min:0',
            'prime_cost' => 'required|numeric|min:0',
            'image' => 'required|file|mimes:jpg,jpeg,bmp,png,webp,gif',
            'units' => 'required',
        ];
    }
}
