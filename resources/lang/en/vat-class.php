<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'VAT class',
        'plural' => 'VAT classes',
        'name' => [
            'label' => 'Description',
            'hint' => 'This description will be displayed to customers during checkout.',
        ],
        'vat_percentage' => [
            'label' => 'VAT percentage',
        ],
    ],

    /**
     * Request / validation
     */
    'request' => [
        'name' => [
            'required' => 'You have not given this shipping size a description.',
            'max' => 'The description can not exceed 255 characters.',
        ],
        'vat_percentage' => [
            'required' => 'You have not entered a vat percentage.',
        ],
    ],
];
