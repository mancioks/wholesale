<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCartRequest extends FormRequest
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
        $count = count((array)$this->post('item'));

        return [
            'qty' => ['bail', 'array', 'required', 'size:' . $count],
            'item' => ['bail', 'array', 'required'],
            'qty.*' => 'integer|min:1|max:5000',
            'item.*' => Rule::exists('cart', 'product_id')->where('user_id', auth()->user()->id),
        ];
    }
}
