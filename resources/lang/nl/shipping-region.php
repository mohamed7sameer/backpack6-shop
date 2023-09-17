<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'regio',
        'plural' => 'regio\'s',
        'name' => [
            'label' => 'Omschrijving',
            'hint' => 'Deze omschrijving wordt ook aan de klant getoond tijdens het uitchecken.',
        ],
        'countries' => [
            'label' => 'Landen',
            'filter-label' => 'Lijst met landen filteren',
            'filter-hint' => 'Start met typen om de lijst met landen te filteren',
            'select-unselected-caption' => 'Selecteer niet eerder toegewezen landen',
            'select-all-caption' => 'Selecteer alle',
            'select-none-caption' => 'Selecteer geen',
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
