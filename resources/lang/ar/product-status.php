<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'product status',
        'plural' => 'product statuses',
        'status' => [
            'label' => 'حالة',
        ],
        'slug' => [
            'label' => 'Slug',
            'hint' => 'تستخدم للمرشحات',
        ],
        'sales_allowed' => [
            'label' => 'السماح بالمبيعات للمنتجات بهذه الحالة',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'status' => [
            'required' => 'لم تقم بإعطاء هذه الحالة وصفا.',
            'min' => 'يجب أن يتكون وصف الحالة من 3 أحرف على الأقل.',
            'max' => 'لا يمكن أن يتجاوز وصف الحالة 255 حرفًا.',
        ],
        'slug' => [
            'required_with' => 'A slug of at least 3 characters is required.',
            'min' => 'The slug should be at least 3 characters.',
            'max' => 'The slug can not exceed 255 characters.',
        ],
    ],
];
