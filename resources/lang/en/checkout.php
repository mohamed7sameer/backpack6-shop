<?php

return [

    /**
     * Stand-in default "NoPayment" Provider
     */
    'payment' => [
        'no_payment' => 'No payment',
    ],

    /**
     * Request / validation
     */
    'request' => [
        'email' => [
            'required' => 'You have not entered your email address.',
            'email' => 'Please enter a valid email address.',
        ],
        'name' => [
            'required' => 'You have not entered your name.',
        ],
        'address' => [
            'required' => 'You have not entered your address.',
        ],
        'zipcode' => [
            'required' => 'You have not entered your zipcode.',
        ],
        'city' => [
            'required' => 'You have not entered your city.',
        ],
        'country' => [
            'required' => 'You have not entered your country.',
        ],
        'redirect_url' => [
            'required' => 'Please make sure the form submits a redirect url. If you are a customer and see this message, please contact the store owner and give them this message.',
        ],
    ],
];
