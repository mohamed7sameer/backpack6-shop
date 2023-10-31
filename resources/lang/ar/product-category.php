<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'product-category',
        'plural' => 'product categories',
        'tabs' => [
            'info' => 'معلومة',
            'media' => 'وسائط',
            'seo' => 'تحسين محركات البحث'
        ],
        'name' => [
            'label' => 'عنوان الفئة',
        ],
        'slug' => [
            'label' => 'Slug',
            'hint' => 'يُستخدم لعنوان URL للفئة',
        ],
        'description' => [
            'label' => 'وصف',
        ],
        'cover' => [
            'label' => 'صورة الغلاف',
        ],
        'meta-title' => [
            'label' => 'SEO/Meta title',
            'hint' => 'عنوان الصفحة التي سيتم إظهارها عند مشاركة الصفحة باستخدام وسائل التواصل الاجتماعي، وكذلك في نتائج محرك البحث (العنوان تحته خط باللون الأزرق في Google).',
        ],
        'meta-description' => [
            'label' => 'SEO/Meta description',
            'hint' => 'مقدمة مختصرة عن الصفحة المراد إظهارها عند مشاركة الصفحة باستخدام وسائل التواصل الاجتماعي وكذلك في محركات البحث (نص أسود في جوجل).',
        ],
        'meta-image' => [
            'label' => 'SEO/Meta image',
            'hint' => 'تظهر هذه الصورة عند مشاركة الصفحة على وسائل التواصل الاجتماعي.',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'name' => [
            'required' => 'لم تقم بإعطاء هذه الفئة عنوانا.',
            'min' => 'يجب أن يتكون العنوان من 3 أحرف على الأقل.',
            'max' => 'لا يمكن أن يتجاوز العنوان 255 حرفًا.',
        ],
        'slug' => [
            'required_with' => 'A slug of at least 3 characters is required.',
            'min' => 'The slug should be at least 3 characters.',
            'max' => 'The slug can not exceed 255 characters.',
        ],
    ],
];
