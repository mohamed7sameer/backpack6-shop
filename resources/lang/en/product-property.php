<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'product property',
        'plural' => 'product properties',
        'title' => [
            'label' => 'Title/label',
        ],
        'slug' => [
            'label' => 'Slug',
            'hint' => 'Used either for the URL or for filtering purposes',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'title' => [
            'required' => 'You have not given this property a title.',
            'max' => 'The title can not exceed 255 characters.',
        ],
        'slug' => [
            'required_with' => 'A slug is required.',
            'max' => 'The slug can not exceed 255 characters.',
        ],
    ],
];
