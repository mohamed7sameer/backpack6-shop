<?php

return [

    /**
     * Request / validation
     */
    'request' => [
        'product_id' => [
            'required' => 'خطأ: لم يتم توفير معرف المنتج.',
            'exist' => 'خطأ: المنتج المطلوب غير موجود.',
        ],
        'quantity' => [
            'required' => 'خطأ: لم يتم توفير الكمية.',
            'integer' => 'خطأ: لم يتم توفير كمية صالحة.',
        ],
    ],


    /**
     * Default controller json response messages
     */
    'controller' => [
        'add' => [
            'success' => 'تمت إضافة المنتج إلى سلة التسوق',
        ],
        'update' => [
            'success' => 'تم تحديث سلة التسوق',
        ],
        'remove' => [
            'success' => 'تمت إزالة المنتج من سلة التسوق',
        ],
    ],
];
