<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'product',
        'plural' => 'producten',
        'tabs' => [
            'info' => 'Info',
            'extras' => 'Eigenschappen',
            'media' => 'Media',
            'sales' => 'Verkoop',
            'shipping' => 'Verzending',
            'variations' => 'Varianten',
            'seo' => 'SEO'
        ],
        'name' => [
            'label' => 'Productnaam',
        ],
        'slug' => [
            'label' => 'Slug',
            'hint' => 'Wordt gebruikt in de URL van de productpagina',
        ],
        'sku' => [
            'label' => 'SKU',
        ],
        'product_categories' => [
            'label' => 'Categorieën',
        ],
        'product_status_id' => [
            'label' => 'Status',
        ],
        'description' => [
            'label' => 'Omschrijving',
        ],
        'properties' => [
            'label' => 'Eigenschappen',
            'new_item_label' => 'Eigenschap toevoegen',
            'property_id' => [
                'label' => 'Eigenschap',
            ],
            'value' => [
                'label' => 'Waarde',
            ],
        ],
        'cover' => [
            'label' => 'Cover afbeelding',
        ],
        'photos' => [
            'label' => 'Foto\'s',
            'new_item_label' => 'Foto toevoegen',
            'photo' => [
                'label' => 'Foto',
            ],
            'description' => [
                'label' => 'Omschrijving',
                'hint' => 'De omschrijving wordt getoond in de vergroting pop-up.',
            ],
        ],
        'price' => [
            'label' => 'Prijs',
        ],
        'sale_price' => [
            'label' => 'Aanbiedingsprijs',
        ],
        'vat_class_id' => [
            'label' => 'BTW groep',
        ],
        'shipping_sizes' => [
            'label' => 'Verzendmethodes',
            'new_item_label' => 'Verzendmethode toevoegen',
            'shipping_size_id' => [
                'label' => 'Verzendmethode',
            ],
            'max_product_count' => [
                'label' => 'Max. producten',
                'hint' => 'Max. producten in verpakking (0 = oneindig).',
            ],
        ],
        'shipping_weight' => [
            'label' => 'Gewicht voor verzending',
            'suffix' => 'gram',
        ],
        'variations' => [
            'label' => 'Varianten',
            'new_item_label' => 'Variant toevoegen',
            'id' => [
                'label' => 'Uniek ID',
            ],
            'description' => [
                'label' => 'Omschrijving',
            ],
            'photo' => [
                'label' => 'Foto',
            ],
            'price' => [
                'label' => 'Prijs',
                'hint' => 'Laat leeg voor standaardprijs',
            ],
            'sale_price' => [
                'label' => 'Aanbiedingsprijs',
            ],
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
            'required' => 'Je hebt geen naam ingevuld voor het product.',
            'min' => 'De naam moet minimaal 3 tekens lang zijn.',
            'max' => 'De naam mag niet langer zijn dan 255 tekens.',
        ],
        'slug' => [
            'required_with' => 'Je hebt geen slug ingevuld voor het product.',
            'min' => 'De slug moet minimaal 5 tekens lang zijn.',
            'max' => 'De slug mag niet langer zijn dan 255 tekens.',
        ],
        'product_status_id' => [
            'required' => 'Je hebt geen status gekozen.',
            'exists' => 'De geselecteerde status bestaat niet (meer) in de database.',
        ],
        'price' => [
            'required' => 'Je hebt geen prijs ingevuld (0 is ook een optie als je dit product gratis wil weggeven).',
        ],
        'vat_class_id' => [
            'required' => 'Je hebt niet gekozen welke BTW groep moet worden toegepast op dit product.',
            'exists' => 'De geselecteerde BTW groep bestaat niet (meer) in de database.',
        ],
        'shipping_sizes' => [
            'required_if' => 'Je hebt geen verzendmethode(s) ingevuld voor dit product.',
            'shipping_size_id' => [
                'required_if' => 'Je geen verzendmethode geselecteerd.',
                'exists' => 'De geselecteerde verzendmethode bestaat niet (meer) in de database.',
            ],
            'max_product_count' => [
                'required' => 'Vul a.u.b. in hoeveel producten passen in deze verzendmethode.',
            ],
        ],
        'shipping_weight' => [
            'required_if' => 'Je hebt niet ingevuld hoeveel het product weegt voor verzending.',
        ],
        'variations' => [
            'id' => [
                'required' => 'Je hebt niet voor iedere variant een unieke ID ingevuld.'
            ],
            'description' => [
                'required' => 'Je hebt niet voor iedere variant een omschrijving ingevuld.'
            ],
        ],
    ],
];
