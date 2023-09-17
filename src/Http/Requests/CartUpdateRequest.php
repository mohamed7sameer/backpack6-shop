<?php

namespace Eleven59\BackpackShop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_id.required' => __('backpack-shop::cart.request.product_id.required'),
            'product_id.exists' => __('backpack-shop::cart.request.product_id.exists'),
            'quantity.required' => __('backpack-shop::cart.request.quantity.required'),
            'quantity.integer' => __('backpack-shop::request.product.slug.quantity.integer'),
        ];
    }
}
