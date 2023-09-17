<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'shipping rule',
        'plural' => 'shipping rules',
        'shipping_region_id' => [
            'label' => 'Shipping region',
        ],
        'shipping_size_id' => [
            'label' => 'Shipping size',
        ],
        'max_weight' => [
            'label' => 'Max. weight',
            'suffix' => 'grams',
        ],
        'price' => [
            'label' => 'Shipping fee',
        ],
        'vat_class_id' => [
            'label' => 'VAT class',
            'hint' => 'If selected, this overrules the VAT class for all products in the shopping cart. This may be useful when international shipping to this region requires 0% VAT for example.',
        ],
        'shipping_vat_class_id' => [
            'label' => 'VAT class for shipping',
            'hint' => 'The VAT percentage to be applied to the shipping fee.',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'shipping_region_id' => [
            'required' => 'You have not selected to which shipping region this rule applies.',
            'exists' => 'This shipping region does not (or no longer) exist in the database.',
        ],
        'shipping_size_id' => [
            'required_if' => 'You have not selected a shipping size.',
            'exists' => 'The shipping size does not (or no longer) exist in the database.',
        ],
        'max_weight' => [
            'required_if' => 'You have not entered a maximum weight for this shipping rule.',
        ],
        'price' => [
            'required' => 'You have not entered a shipping fee (0 is also ok).',
        ],
        'vat_class_id' => [
            'exists' => 'The VAT class you selected as an override for this shipping rule does not (or no longer) exist in the database.',
        ],
        'shipping_vat_class_id' => [
            'required_unless' => 'You have not selected which VAT class to apply to the shipping fee.',
            'exists' => 'The VAT class you selected for shipping does not (or no longer) exist in the database.',
        ],
    ],
];
