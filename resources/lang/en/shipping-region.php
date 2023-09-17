<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'shipping region',
        'plural' => 'shipping regions',
        'name' => [
            'label' => 'Description',
            'hint' => 'This description will be displayed to customers during checkout.',
        ],
        'countries' => [
            'label' => 'Countries',
            'filter-label' => 'Filter countries list',
            'filter-hint' => 'Start typing to start filtering',
            'select-unselected-caption' => 'Select all previously unmapped countries',
            'select-all-caption' => 'Select all',
            'select-none-caption' => 'Select none',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'name' => [
            'required' => 'You have not given this shipping region a description.',
            'max' => 'The description can not exceed 255 characters.',
        ],
    ],
];
