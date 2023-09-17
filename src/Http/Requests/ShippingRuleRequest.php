<?php

namespace Eleven59\BackpackShop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use function backpack_auth;

class ShippingRuleRequest extends FormRequest
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
            'shipping_region_id' => 'required|exists:shipping_regions,id',
            'shipping_size_id' => [Rule::requiredIf(bpshop_shipping_size_enabled()), 'exists:shipping_sizes,id'],
            'max_weight' => [Rule::requiredIf(bpshop_shipping_weight_enabled())],
            'price' => 'required',
            'shipping_vat_class_id' => 'required_unless:price,0|exists:vat_classes,id',
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
            'shipping_region_id.required' => __('backpack-shop::shipping-rule.request.shipping_region_id.required'),
            'shipping_region_id.exists' => __('backpack-shop::shipping-rule.request.shipping_region_id.exists'),
            'shipping_size_id.required*' => __('backpack-shop::shipping-rule.request.shipping_size_id.required_if'),
            'shipping_size_id.exists' => __('backpack-shop::shipping-rule.request.shipping_size_id.exists'),
            'max_weight.required*' => __('backpack-shop::shipping-rule.request.max_weight.required_if'),
            'price.required' => __('backpack-shop::shipping-rule.request.price.required'),
            'shipping_vat_class_id.required_unless' => __('backpack-shop::shipping-rule.request.shipping_vat_class_id.required_unless'),
            'shipping_vat_class_id.exists' => __('backpack-shop::shipping-rule.request.shipping_vat_class_id.exists'),
        ];
    }
}
