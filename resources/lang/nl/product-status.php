<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'productstatus',
        'plural' => 'productstatussen',
        'status' => [
            'label' => 'Status',
        ],
        'slug' => [
            'label' => 'Slug',
            'hint' => 'Wordt gebruikt voor filters.',
        ],
        'sales_allowed' => [
            'label' => 'Sta verkoop toe voor producten met deze status',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'status' => [
            'required' => 'Je hebt geen omschrijving ingevuld voor de status.',
            'min' => 'De statusomschrijving moet minimaal 3 tekens lang zijn.',
            'max' => 'De statusomschrijving mag niet langer zijn dan 255 tekens.',
        ],
        'slug' => [
            'required_with' => 'Je hebt geen slug ingevuld voor de status.',
            'min' => 'De slug moet minimaal 3 tekens lang zijn.',
            'max' => 'De slug mag niet langer zijn dan 255 tekens.',
        ],
    ],
];
