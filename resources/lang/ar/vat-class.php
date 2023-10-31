<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'VAT class',
        'plural' => 'VAT classes',
        'name' => [
            'label' => 'وصف',
            'hint' => 'سيتم عرض هذا الوصف للعملاء أثناء الخروج.',
        ],
        'vat_percentage' => [
            'label' => 'نسبة ضريبة القيمة المضافة',
        ],
    ],

    /**
     * Request / validation
     */
    'request' => [
        'name' => [
            'required' => 'لم تقم بإعطاء وصف لحجم الشحن هذا.',
            'max' => 'لا يمكن أن يتجاوز الوصف 255 حرفًا.',
        ],
        'vat_percentage' => [
            'required' => 'لم تقم بإدخال نسبة ضريبة القيمة المضافة.',
        ],
    ],
];
