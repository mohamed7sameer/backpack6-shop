<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'shipping size',
        'plural' => 'shipping sizes',
        'list-notice' => [
            'heading' => 'Important!',
            'content' => 'Please order your shipping sizes using the "reorder" button below. The shipping calculator will assume the list is ordered from small to big, which means that products are assumed to always also fit in bigger containers.'
        ],
        'reorder-notice' => [
            'heading' => 'Please note!',
            'content' => 'Even if the order below already looks good, please save again to make sure the order is saved to the database as well.',
        ],
        'name' => [
            'label' => 'Description',
            'hint' => 'This description will be displayed to customers during checkout.',
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
    ],
];
