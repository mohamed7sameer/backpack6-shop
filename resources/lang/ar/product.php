<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'product',
        'plural' => 'products',
        'tabs' => [
            'info' => 'معلومة',
            'extras' => 'سمات',
            'media' => 'وسائط',
            'sales' => 'مبيعات',
            'shipping' => 'شحن',
            'variations' => 'الاختلافات',
            'seo' => 'تحسين محركات البحث'
        ],
        'name' => [
            'label' => 'اسم المنتج',
        ],
        'slug' => [
            'label' => 'Slug',
            'hint' => 'Used for the product URL',
        ],
        'sku' => [
            'label' => 'رمز التخزين التعريفي (SKU)',
        ],
        'product_categories' => [
            'label' => 'فئات',
        ],
        'product_status_id' => [
            'label' => 'حالة',
        ],
        'description' => [
            'label' => 'وصف',
        ],
        'properties' => [
            'label' => 'الخصائص',
            'new_item_label' => 'أضف خاصية',
            'property_id' => [
                'label' => 'خاصية',
            ],
            'value' => [
                'label' => 'قيمة',
            ],
        ],
        'cover' => [
            'label' => 'صورة الغلاف',
        ],
        'photos' => [
            'label' => 'الصور',
            'new_item_label' => 'إضافة صورة',
            'photo' => [
                'label' => 'صورة',
            ],
            'description' => [
                'label' => 'وصف',
                'hint' => 'سيتم عرض هذا عند تكبير الصورة في النافذة المنبثقة.',
            ],
        ],
        'price' => [
            'label' => 'السعر',
        ],
        'sale_price' => [
            'label' => '(اتركها فارغه لو لم يوجد خصم) السعر قبل الخصم',
        ],
        'vat_class_id' => [
            'label' => 'فئة ضريبة القيمة المضافة',
        ],
        'shipping_sizes' => [
            'label' => 'أحجام الشحن',
            'new_item_label' => 'إضافة حجم جديد',
            'shipping_size_id' => [
                'label' => 'حجم الشحن',
            ],
            'max_product_count' => [
                'label' => 'الأعلى. منتجات',
                'hint' => 'الأعلى. العناصر التي تناسب حجم الشحن هذا (0 = لانهائي).',
            ],
        ],
        'shipping_weight' => [
            'label' => 'وزن المشتريات',
            'suffix' => 'جرامات',
        ],
        'variations' => [
            'label' => 'الاختلافات',
            'new_item_label' => 'إضافة صيغة جديدة',
            'id' => [
                'label' => 'معرف فريد',
            ],
            'description' => [
                'label' => 'الاختلاف',
            ],
            'photo' => [
                'label' => 'صورة',
            ],
            'price' => [
                'label' => 'سعر',
                'hint' => 'اتركه فارغًا للسعر الافتراضي',
            ],
            'sale_price' => [
                'label' => 'سعر البيع',
            ],
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
            'required' => 'لم تقم بإعطاء هذا المنتج اسما.',
            'min' => 'يجب أن يتكون اسم المنتج من 3 أحرف على الأقل.',
            'max' => 'لا يمكن أن يتجاوز اسم المنتج 255 حرفًا.',
        ],
        'slug' => [
            'required_with' => 'A slug of at least 5 characters is required.',
            'min' => 'The slug should be at least 5 characters.',
            'max' => 'The slug can not exceed 255 characters.',
        ],
        'product_status_id' => [
            'required' => 'لم تقم بتحديد حالة المنتج.',
            'exists' => 'حالة المنتج غير موجودة (أو لم تعد) موجودة في قاعدة البيانات.',
        ],
        'price' => [
            'required' => 'لم تقم بإدخال سعر لهذا المنتج (0 لا بأس به أيضًا، إذا كنت تريد منحه مجانًا).',
        ],
        'vat_class_id' => [
            'required' => 'لم تقم بتحديد فئة ضريبة القيمة المضافة التي يجب تطبيقها على هذا المنتج.',
            'exists' => 'فئة ضريبة القيمة المضافة غير موجودة (أو لم تعد) موجودة في قاعدة البيانات.',
        ],
        'shipping_sizes' => [
            'required_if' => 'لم تقم بتحديد حجم (أحجام) الشحن التي يتطلبها هذا المنتج.',
            'shipping_size_id' => [
                'required' => 'لم تقم بتحديد حجم الشحن.',
                'exists' => 'حجم الشحن غير موجود (أو لم يعد) موجودًا في قاعدة البيانات.',
            ],
            'max_product_count' => [
                'required' => 'يرجى إضافة عدد المنتجات المناسبة.',
            ],
        ],
        'shipping_weight' => [
            'required_if' => 'لم تقم بإدخال وزن الشحن لهذا المنتج.',
        ],
        'variations' => [
            'id' => [
                'required' => 'الرجاء تقديم معرف فريد لكل شكل.'
            ],
            'description' => [
                'required' => 'الرجاء إدخال وصف لكل الاختلاف.'
            ],
        ],
    ],
];
