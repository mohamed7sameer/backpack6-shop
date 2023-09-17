<?php

return [

    /**
     * Request / validation
     */
    'request' => [
        'product_id' => [
            'required' => 'Error: geen product id ingevuld.',
            'exist' => 'Error: dit product bestaat niet (meer).',
        ],
        'quantity' => [
            'required' => 'Error: geen hoeveelheid ingevuld.',
            'integer' => 'Error: geen geldige hoeveelheid ingevuld.',
        ],
    ],


    /**
     * Default controller json response messages
     */
    'controller' => [
        'add' => [
            'success' => 'Product toegevoegd aan winkelwagen',
        ],
        'update' => [
            'success' => 'Winkelwagen bijgewerkt',
        ],
        'remove' => [
            'success' => 'Product verwijders uit winkelwagen',
        ],
    ],
];
