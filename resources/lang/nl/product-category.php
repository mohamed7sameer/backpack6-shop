<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'productcategorie',
        'plural' => 'productcategorieën',
        'tabs' => [
            'info' => 'Informatie',
            'media' => 'Media',
            'seo' => 'SEO/Delen/Meta'
        ],
        'name' => [
            'label' => 'Naam',
        ],
        'slug' => [
            'label' => 'Slug',
            'hint' => 'Wordt gebruikt als URL voor deze categorie.',
        ],
        'description' => [
            'label' => 'Omschrijving',
        ],
        'cover' => [
            'label' => 'Cover afbeelding',
        ],
        'meta-title' => [
            'label' => 'SEO/Meta titel',
            'hint' => 'De titel van de pagina, te tonen bij het delen via sociale media alsmede in zoeksites (blauwe onderstreepte titel in Google, bijvoorbeeld).',
        ],
        'meta-description' => [
            'label' => 'SEO/Meta description',
            'hint' => 'De omschrijving van de pagina, te tonen bij het delen via sociale media alsmede in zoeksites (zwarte korte tekst in Google, bijvoorbeeld).',
        ],
        'meta-image' => [
            'label' => 'SEO/Meta image',
            'hint' => 'Deze afbeelding wordt getoond bij het delen van de pagina via sociale media.',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'name' => [
            'required' => 'Je hebt geen naam ingevuld voor de categorie.',
            'min' => 'De naam moet minimaal 3 tekens lang zijn.',
            'max' => 'De naam mag niet langer zijn dan 255 tekens.',
        ],
        'slug' => [
            'required_with' => 'Je hebt geen slug ingevuld voor de categorie.',
            'min' => 'De slug moet minimaal 3 tekens lang zijn.',
            'max' => 'De slug mag niet langer zijn dan 255 tekens.',
        ],
    ],
];
