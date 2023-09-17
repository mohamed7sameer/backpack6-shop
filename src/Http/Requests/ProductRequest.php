<?php

namespace Eleven59\BackpackShop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use function backpack_auth;

class ProductRequest extends FormRequest
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
            'name' => 'required|min:5|max:255',
            'slug' => 'required_with:name|min:5|max:255',
            'product_status_id' => 'required|exists:product_statuses,id',
            'price' => 'required',
            'vat_class_id' => 'required|exists:vat_classes,id',
            'shipping_sizes' => [Rule::requiredIf(bpshop_shipping_size_enabled())],
            'shipping_sizes.*.shipping_size_id' => 'required|exists:shipping_sizes,id',
            'shipping_sizes.*.max_product_count' => 'required',
            'shipping_weight' => [Rule::requiredIf(bpshop_shipping_weight_enabled())],
            'variations.*.id' => 'required',
            'variations.*.description' => 'required',
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
            'name.required' => __('backpack-shop::product.request.name.required'),
            'name.min' => __('backpack-shop::product.request.name.min'),
            'name.max' => __('backpack-shop::product.request.name.max'),
            'slug.required_with' => __('backpack-shop::product.request.slug.required_with'),
            'slug.min' => __('backpack-shop::product.request.slug.min'),
            'slug.max' => __('backpack-shop::product.request.slug.max'),
            'product_status_id.required' => __('backpack-shop::product.request.product_status_id.required'),
            'product_status_id.exists' => __('backpack-shop::product.request.product_status_id.exists'),
            'price.required' => __('backpack-shop::product.request.price.required'),
            'vat_class_id.required' => __('backpack-shop::product.request.vat_class_id.required'),
            'vat_class_id.exists' => __('backpack-shop::product.request.vat_class_id.exists'),
            'shipping_sizes.required*' => __('backpack-shop::product.request.shipping_sizes.required_if'),
            'shipping_sizes.*.shipping_size_id.required' => __('backpack-shop::product.request.shipping_sizes.shipping_size_id.required'),
            'shipping_sizes.*.max_product_count.required' => __('backpack-shop::product.request.shipping_sizes.max_product_count.required'),
            'shipping_weight.required*' => __('backpack-shop::product.request.shipping_weight.required_if'),
            'variations.*.id.required' => __('backpack-shop::product.request.variations.id.required'),
            'variations.*.description.required' => __('backpack-shop::product.request.variations.description.required'),
        ];
    }

    protected function prepareForValidation()
    {
        if(isset($this->variations) && is_array($this->variations)) {
            $variations = $this->variations;
            foreach($variations as $key => $variation)
            {
                $variations[$key]['id'] = Str::slug($variation['id']);
            }
            $this->merge([
                'variations' => $variations,
            ]);
        }

        $this->merge([
            'slug' => Str::slug($this->slug),
        ]);
    }
}
