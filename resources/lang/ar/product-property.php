<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'product property',
        'plural' => 'product properties',
        'title' => [
            'label' => 'العنوان/التسمية',
        ],
        'slug' => [
            'label' => 'Slug',
            'hint' => 'يُستخدم إما لعنوان URL أو لأغراض التصفية',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'title' => [
            'required' => 'لم تقم بإعطاء هذه الخاصية عنوانًا.',
            'max' => 'لا يمكن أن يتجاوز العنوان 255 حرفًا.',
        ],
        'slug' => [
            'required_with' => 'A slug is required.',
            'max' => 'The slug can not exceed 255 characters.',
        ],
    ],
];
