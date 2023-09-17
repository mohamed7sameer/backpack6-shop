<?php

return [

    /**
     * Description for payment
     */
    'payment-description' => 'Your order',
    'error-processing' => 'Something went wrong processing your order. Please try again or contact us if this keeps happening.',

    /**
     * Statuses
     */
    'statuses' => [
        'paid' => 'Paid',
        'cancelled' => 'Cancelled',
        'new' => 'New',
    ],
    'status-messages' => [
        'paid' => 'Thank you for your order. You have received a confirmation email. We will let you know when your order has been dispatched.',
        'new' => 'Your order went through, but your payment is still processing. You will receive a confirmation email for your order once your payment is processed correctly (usually this happens automatically within a couple of hours). Please get in touch if this takes more than a couple of hours.',
        'cancelled' => 'The payment provider reported that the payment did not go through. The order is therefore cancelled. We saved your cart, so you can easily try again if something went wrong or you cancelled the payment accidentally.',
        'error' => 'Something went wrong with your payment. Please contact us to check what happened. Please make sure to check whether the amount was withdrawn from your payment account, and include this information in your message to us.',
    ],

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'order',
        'plural' => 'orders',
        'details' => 'Details',
        'payment-description' => 'Thanks you for your :store_name order',
        'created_at' => [
            'label' => 'Created',
        ],
        'order_no' => [
            'label' => 'Order no.',
        ],
        'full_address' => [
            'label' => 'Address',
        ],
        'order_items' => [
            'label' => 'Products',
        ],
        'order_total' => [
            'label' => 'Total',
        ],
        'status' => [
            'label' => 'Status',
        ],
    ],

    /**
     * Details
     */
    'details' => [
        'title' => 'Order (:order_no)',
        'phone' => 'Phone no.',
        'address' => 'Address',
        'invoice_no' => 'Invoice no.',
        'invoice_date' => 'Date',
        'order_summary' => 'Order summary',
        'excl_vat' => 'excl. VAT',
        'vat' => 'VAT',
        'incl_vat' => 'incl. VAT',
        'subtotal' => 'Subtotal',
        'shipping' => 'Shipping',
        'total' => 'Total',
        'status' => 'Order status: :status',
    ],
];
