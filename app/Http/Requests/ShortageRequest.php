<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShortageRequest extends FormRequest
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
        $productCount = count($this->post('product'));

        return [
            'product' => 'required|array',
            'stock' => 'required|array|size:' . $productCount,
            'product.*' => 'required|exists:order_items,id',
            'stock.*' => 'numeric|integer|nullable|min:0',
        ];
    }

    protected function prepareForValidation()
    {
        $productStocks = $this->post('stock');

        foreach ($productStocks as $key => $productStock) {
            if($productStock === '')
                $productStocks[$key] = null;
        }

        $this->merge(['stock' => $productStocks]);
    }
}
