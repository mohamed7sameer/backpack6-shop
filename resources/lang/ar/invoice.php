<?php

return [

    /**
     * Mail
     */
    'mail' => [
        'title' => 'طلبك (:order_no)',
        'title_copy' => 'طلب جديد (:order_no)',
        'subject' => 'فاتورة',
        'copy' => 'وهذه نسخة من الفاتورة التي تم إرسالها للعميل.',
        'dear-customer' => "<p>عزيزي :customer,<br>&nbsp;<br>شكرا لطلبك. تؤكد هذه الرسالة الإلكترونية أن طلبك قد تمت معالجته بنجاح.</p>",
        'bye-customer' => "<p>شكرًا مرة أخرى، نأمل أن تستمتع بطلبك بمجرد وصوله. <br>&nbsp;<br>أحر التحيات,<br>&nbsp;<br>:store</p>",
        'order_summary' => 'ملخص الطلب',
        'subtotal' => 'المجموع الفرعي',
        'shipping' => 'شحن',
        'total' => 'المجموع',
    ],

    /**
     * PDF
     */
    'pdf' => [
        'title' => 'طلبك (:order_no)',
        'title_copy' => 'طلب جديد (:order_no)',
        'phone' => 'رقم الهاتف.',
        'address' => 'عنوان',
        'invoice_no' => 'رقم الفاتورة.',
        'invoice_date' => 'تاريخ',
        'order_summary' => 'ملخص الطلب',
        'subtotal' => 'المجموع الفرعي',
        'shipping' => 'شحن',
        'total' => 'المجموع',
        'thanks' => 'شكرا لطلبك',
    ],
];
