<?php

return [

    /**
     * Stand-in default "NoPayment" Provider
     */
    'payment' => [
        'no_payment' => 'Geen betaling',
    ],

    /**
     * Request / validation
     */
    'request' => [
        'email' => [
            'required' => 'Je hebt je e-mailadres niet ingevuld.',
            'email' => 'Je hebt geen geldig e-mailadres ingevuld.',
        ],
        'name' => [
            'required' => 'Je hebt je naam niet ingevuld.',
        ],
        'address' => [
            'required' => 'Je hebt je adres niet ingevuld.',
        ],
        'zipcode' => [
            'required' => 'Je hebt je postcode niet ingevuld.',
        ],
        'city' => [
            'required' => 'Je hebt je woonplaats niet ingevuld.',
        ],
        'country' => [
            'required' => 'Je hebt je land niet ingevuld.',
        ],
        'redirect_url' => [
            'required' => 'Geen redirect url beschikbaar. Mocht je deze melding krijgen als klant, stuur deze dan door naar ons, dan kunnen we onderzoeken waar het mis ging.',
        ],
    ],
];
