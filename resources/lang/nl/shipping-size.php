<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'verzendmethode',
        'plural' => 'verzendmethodes',
        'list-notice' => [
            'heading' => 'Belangrijk!',
            'content' => 'Zorg er a.u.b. voor dat je de onderstaande verzendmethodes via de "Rangschik verzendmethodes" knop op de juiste volgorde van klein naar groot zet. De berekening voor verzendkosten gaat er vanuit dat producten altijd in een grotere verzenddoos zullen passen.',
        ],
        'reorder-notice' => [
            'heading' => 'Let op!',
            'content' => 'Ook als de volgorde al goed lijkt, klik dan nog eens op opslaan. Alleen dan weet je zeker dat de volgorde ook in de database is opgeslagen.',
        ],
        'name' => [
            'label' => 'Omschrijving',
            'hint' => 'Deze omschrijving wordt ook aan de klant getoond tijdens het uitchecken.',
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
    ],
];
