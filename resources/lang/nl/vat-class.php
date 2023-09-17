<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'BTW groep',
        'plural' => 'BTW groepen',
        'name' => [
            'label' => 'Omschrijving',
            'hint' => 'Deze omschrijving wordt ook aan de klant getoond tijdens het uitchecken.',
        ],
        'vat_percentage' => [
            'label' => 'BTW percentage',
        ],
    ],

    /**
     * Request / validation
     */
    'request' => [
        'name' => [
            'required' => 'Je hebt geen omschrijving ingevuld.',
            'max' => 'De omschrijving mag niet langer zijn dan 255 tekens.',
        ],
        'vat_percentage' => [
            'required' => 'Je hebt geen BTW percentage ingevuld.',
        ],
    ],
];
