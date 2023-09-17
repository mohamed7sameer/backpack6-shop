<?php

namespace Eleven59\BackpackShop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'email' => 'required|email',
            'name' => 'required',
            'address' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'country' => 'required',
            'redirect_url' => 'required',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'email.required' => __('backpack-shop::checkout.request.email.required'),
            'email.email' => __('backpack-shop::checkout.request.email.email'),
            'name.required' => __('backpack-shop::checkout.request.name.required'),
            'address.required' => __('backpack-shop::checkout.request.address.required'),
            'zipcode.required' => __('backpack-shop::checkout.request.zipcode.required'),
            'city.required' => __('backpack-shop::checkout.request.city.required'),
            'country.required' => __('backpack-shop::checkout.request.country.required'),
            'redirect_url.required' => __('backpack-shop::checkout.request.redirect_url.required'),
        ];
    }
}
