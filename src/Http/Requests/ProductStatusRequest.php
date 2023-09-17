<?php

namespace Eleven59\BackpackShop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use function backpack_auth;

class ProductStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|min:3|max:255',
            'slug' => 'required_with:status|min:3|max:255',
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
            'status.required' => __('backpack-shop::product-status.request.status.required'),
            'status.min' => __('backpack-shop::product-status.request.status.min'),
            'status.max' => __('backpack-shop::product-status.request.status.max'),
            'slug.required_with' => __('backpack-shop::product-status.request.slug.required_with'),
            'slug.min' => __('backpack-shop::product-status.request.slug.min'),
            'slug.max' => __('backpack-shop::product-status.request.slug.max'),
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->slug),
        ]);
    }
}
