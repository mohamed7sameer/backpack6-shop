<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'shipping size',
        'plural' => 'shipping sizes',
        'list-notice' => [
            'heading' => 'مهم!',
            'content' => 'يرجى طلب أحجام الشحن الخاصة بك باستخدام زر "إعادة الطلب" أدناه. ستفترض حاسبة الشحن أن القائمة مرتبة من الصغيرة إلى الكبيرة، مما يعني أنه من المفترض أن تتناسب المنتجات دائمًا أيضًا مع الحاويات الأكبر حجمًا.'
        ],
        'reorder-notice' => [
            'heading' => 'يرجى الملاحظة!',
            'content' => 'حتى لو كان الطلب أدناه يبدو جيدًا بالفعل، يرجى الحفظ مرة أخرى للتأكد من حفظ الطلب في قاعدة البيانات أيضًا.',
        ],
        'name' => [
            'label' => 'وصف',
            'hint' => 'سيتم عرض هذا الوصف للعملاء أثناء الخروج.',
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
    ],
];
