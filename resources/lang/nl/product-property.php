<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'producteigenschap',
        'plural' => 'producteigenschappen',
        'title' => [
            'label' => 'Titel/label',
        ],
        'slug' => [
            'label' => 'Slug',
            'hint' => 'Wordt gebruikt in URLs of voor filteren',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'title' => [
            'required' => 'Je hebt geen titel/label ingevuld voor het productattribuut.',
            'max' => 'De titel mag niet langer zijn dan 255 tekens.',
        ],
        'slug' => [
            'required_with' => 'Je hebt geen slug ingevuld voor het productattribuut.',
            'max' => 'De slug mag niet langer zijn dan 255 tekens.',
        ],
    ],
];
