<?php

return [

    /**
     * Stand-in default "NoPayment" Provider
     */
    'payment' => [
        'no_payment' => 'لا يوجد دفع',
    ],

    /**
     * Request / validation
     */
    'request' => [
        'email' => [
            'required' => 'لم تقم بإدخال عنوان البريد الإلكتروني الخاص بك.',
            'email' => 'يرجى إدخال عنوان بريد إلكتروني صالح.',
        ],
        'name' => [
            'required' => 'لم تقم بإدخال اسمك.',
        ],
        'address' => [
            'required' => 'لم تقم بإدخال عنوانك.',
        ],
        'zipcode' => [
            'required' => 'لم تقم بإدخال الرمز البريدي الخاص بك.',
        ],
        'city' => [
            'required' => 'لم تدخل مدينتك.',
        ],
        'country' => [
            'required' => 'لم تدخل بلدك.',
        ],
        'redirect_url' => [
            'required' => 'يرجى التأكد من أن النموذج يقدم عنوان URL لإعادة التوجيه. إذا كنت أحد العملاء ورأيت هذه الرسالة، فيرجى الاتصال بصاحب المتجر وإعطائه هذه الرسالة.',
        ],
    ],
];
