<?php

namespace Eleven59\BackpackShop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use function backpack_auth;

class ProductPropertyRequest extends FormRequest
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
            'title' => 'required|max:255',
            'slug' => 'required_with:name|max:255',
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
            'title.required' => __('backpack-shop::product-property.request.title.required'),
            'title.max' => __('backpack-shop::product-property.request.title.max'),
            'slug.required_with' => __('backpack-shop::product-property.request.slug.required_with'),
            'slug.max' => __('backpack-shop::product-property.request.slug.max'),
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->slug),
        ]);
    }
}
