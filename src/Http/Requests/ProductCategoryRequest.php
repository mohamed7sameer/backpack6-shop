<?php

namespace Eleven59\BackpackShop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use function backpack_auth;

class ProductCategoryRequest extends FormRequest
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
            'name' => 'required|min:3|max:255',
            'slug' => 'required_with:name|min:3|max:255',
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
            'name.required' => __('backpack-shop::product-category.request.name.required'),
            'name.min' => __('backpack-shop::product-category.request.name.min'),
            'name.max' => __('backpack-shop::product-category.request.name.max'),
            'slug.required_with' => __('backpack-shop::product-category.request.slug.required_with'),
            'slug.min' => __('backpack-shop::product-category.request.slug.min'),
            'slug.max' => __('backpack-shop::product-category.request.slug.max'),
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->slug),
        ]);
    }
}
