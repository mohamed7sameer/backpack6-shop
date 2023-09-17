<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'product',
        'plural' => 'products',
        'tabs' => [
            'info' => 'Information',
            'extras' => 'Features',
            'media' => 'Media',
            'sales' => 'Sales',
            'shipping' => 'Shipping',
            'variations' => 'Variations',
            'seo' => 'SEO'
        ],
        'name' => [
            'label' => 'Product name',
        ],
        'slug' => [
            'label' => 'Slug',
            'hint' => 'Used for the product URL',
        ],
        'sku' => [
            'label' => 'SKU',
        ],
        'product_categories' => [
            'label' => 'Categories',
        ],
        'product_status_id' => [
            'label' => 'Status',
        ],
        'description' => [
            'label' => 'Description',
        ],
        'properties' => [
            'label' => 'Properties',
            'new_item_label' => 'Add property',
            'property_id' => [
                'label' => 'Property',
            ],
            'value' => [
                'label' => 'Value',
            ],
        ],
        'cover' => [
            'label' => 'Cover image',
        ],
        'photos' => [
            'label' => 'Photos',
            'new_item_label' => 'Add photo',
            'photo' => [
                'label' => 'Photo',
            ],
            'description' => [
                'label' => 'Description',
                'hint' => 'This will be shown when the picture is enlarged in the pop-up.',
            ],
        ],
        'price' => [
            'label' => 'Price',
        ],
        'sale_price' => [
            'label' => 'Sale price',
        ],
        'vat_class_id' => [
            'label' => 'VAT class',
        ],
        'shipping_sizes' => [
            'label' => 'Shipping sizes',
            'new_item_label' => 'Add new size',
            'shipping_size_id' => [
                'label' => 'Shipping size',
            ],
            'max_product_count' => [
                'label' => 'Max. products',
                'hint' => 'Max. items that fit this shipping size (0 = infinite).',
            ],
        ],
        'shipping_weight' => [
            'label' => 'Shipping weight',
            'suffix' => 'grams',
        ],
        'variations' => [
            'label' => 'Variations',
            'new_item_label' => 'Add new variation',
            'id' => [
                'label' => 'Unique ID',
            ],
            'description' => [
                'label' => 'Variation',
            ],
            'photo' => [
                'label' => 'Photo',
            ],
            'price' => [
                'label' => 'Price',
                'hint' => 'Leave empty for default price',
            ],
            'sale_price' => [
                'label' => 'Sale price',
            ],
        ],
        'meta-title' => [
            'label' => 'SEO/Meta title',
            'hint' => 'Title of the page to be shown when sharing the page using social media, as well as in search engine results (blue underlined title in Google).',
        ],
        'meta-description' => [
            'label' => 'SEO/Meta description',
            'hint' => 'Brief introduction to the page to be shown when sharing the page using social media, as well as in search engines (black text in Google).',
        ],
        'meta-image' => [
            'label' => 'SEO/Meta image',
            'hint' => 'This image is shown when sharing the page on social media.',
        ],
    ],

    /**
     * Request / validation
     */
    'request' => [
        'name' => [
            'required' => 'You have not given this product a name.',
            'min' => 'The product name should be at least 3 characters.',
            'max' => 'The product name can not exceed 255 characters.',
        ],
        'slug' => [
            'required_with' => 'A slug of at least 5 characters is required.',
            'min' => 'The slug should be at least 5 characters.',
            'max' => 'The slug can not exceed 255 characters.',
        ],
        'product_status_id' => [
            'required' => 'You have not selected the product status.',
            'exists' => 'The product status does not (or no longer) exist in the database.',
        ],
        'price' => [
            'required' => 'You have not entered a price for this product (0 is also ok, if you want to give this away for free).',
        ],
        'vat_class_id' => [
            'required' => 'You have not selected which VAT class should be applied to this product.',
            'exists' => 'The VAT class does not (or no longer) exist in the database.',
        ],
        'shipping_sizes' => [
            'required_if' => 'You have not defined which shipping size(s) this product requires.',
            'shipping_size_id' => [
                'required' => 'You have not selected a shipping size.',
                'exists' => 'The shipping size does not (or no longer) exist in the database.',
            ],
            'max_product_count' => [
                'required' => 'Please add how many products would fit.',
            ],
        ],
        'shipping_weight' => [
            'required_if' => 'You have not entered this product\'s shipping weight.',
        ],
        'variations' => [
            'id' => [
                'required' => 'Please provide a unique ID for every variation.'
            ],
            'description' => [
                'required' => 'Please enter a description for every variation.'
            ],
        ],
    ],
];
