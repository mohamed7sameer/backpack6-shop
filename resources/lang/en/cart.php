<?php

return [

    /**
     * Request / validation
     */
    'request' => [
        'product_id' => [
            'required' => 'Error: no product id provided.',
            'exist' => 'Error: requested product does not exist.',
        ],
        'quantity' => [
            'required' => 'Error: no quantity provided.',
            'integer' => 'Error: no valid quantity provided.',
        ],
    ],


    /**
     * Default controller json response messages
     */
    'controller' => [
        'add' => [
            'success' => 'Product added to cart',
        ],
        'update' => [
            'success' => 'Cart updated',
        ],
        'remove' => [
            'success' => 'Product removed from cart',
        ],
    ],
];
