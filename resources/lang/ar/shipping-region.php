<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'shipping region',
        'plural' => 'shipping regions',
        'name' => [
            'label' => 'وصف',
            'hint' => 'سيتم عرض هذا الوصف للعملاء أثناء الدفع',
        ],
        'countries' => [
            'label' => 'الدول',
            'filter-label' => 'تصفية قائمة البلدان',
            'filter-hint' => 'ابدأ الكتابة لبدء التصفية',
            'select-unselected-caption' => 'حدد جميع البلدان التي لم يتم تعيينها مسبقًا',
            'select-all-caption' => 'اختر الكل',
            'select-none-caption' => 'لا تختر شيء',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'name' => [
            'required' => 'لم تقم بإعطاء منطقة الشحن هذه وصفًا.',
            'max' => 'لا يمكن أن يتجاوز الوصف 255 حرفًا.',
        ],
    ],
];
