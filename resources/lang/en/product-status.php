<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'product status',
        'plural' => 'product statuses',
        'status' => [
            'label' => 'Status',
        ],
        'slug' => [
            'label' => 'Slug',
            'hint' => 'Used for filters',
        ],
        'sales_allowed' => [
            'label' => 'Allow sales for products with this status',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'status' => [
            'required' => 'You have not given this status a description.',
            'min' => 'The status description should be at least 3 characters.',
            'max' => 'The status description can not exceed 255 characters.',
        ],
        'slug' => [
            'required_with' => 'A slug of at least 3 characters is required.',
            'min' => 'The slug should be at least 3 characters.',
            'max' => 'The slug can not exceed 255 characters.',
        ],
    ],
];
