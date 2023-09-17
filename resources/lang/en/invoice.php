<?php

return [

    /**
     * Mail
     */
    'mail' => [
        'title' => 'Your order (:order_no)',
        'title_copy' => 'New order (:order_no)',
        'subject' => 'Invoice',
        'copy' => 'This is a copy of the invoice that was sent to the customer.',
        'dear-customer' => "<p>Dear :customer,<br>&nbsp;<br>Thanks for your order. This email confirms that your order was succesfully processed.</p>",
        'bye-customer' => "<p>Thanks again, we hope you enjoy your order once it arrives.<br>&nbsp;<br>Warmest regards,<br>&nbsp;<br>:store</p>",
        'order_summary' => 'Order summary',
        'subtotal' => 'Subtotal',
        'shipping' => 'Shipping',
        'total' => 'Total',
    ],

    /**
     * PDF
     */
    'pdf' => [
        'title' => 'Your order (:order_no)',
        'title_copy' => 'New order (:order_no)',
        'phone' => 'Phone no.',
        'address' => 'Address',
        'invoice_no' => 'Invoice no.',
        'invoice_date' => 'Date',
        'order_summary' => 'Order summary',
        'subtotal' => 'Subtotal',
        'shipping' => 'Shipping',
        'total' => 'Total',
        'thanks' => 'Thank you for your order',
    ],
];
