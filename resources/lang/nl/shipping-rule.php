<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'verzendkosten',
        'plural' => 'verzendkosten',
        'shipping_region_id' => [
            'label' => 'Regio',
        ],
        'shipping_size_id' => [
            'label' => 'Verzendmethode',
        ],
        'max_weight' => [
            'label' => 'Max. gewicht',
            'suffix' => 'gram',
        ],
        'price' => [
            'label' => 'Verzendkosten',
        ],
        'vat_class_id' => [
            'label' => 'BTW groep',
            'hint' => 'Als je hier een BTW groep kiest overschrijft deze de BTW voor alle producten in de bestelling. Dit kan handig zijn als voor een bepaalde regio bijvoorbeeld de BTW verlegd moet worden (dus 0% moet zijn).',
        ],
        'shipping_vat_class_id' => [
            'label' => 'BTW groep voor verzendkosten',
            'hint' => 'BTW percentage toe te passen op de verzendkosten zelf.',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'shipping_region_id' => [
            'required' => 'Je hebt niet gekozen voor welke regio deze verzendkosten moeten gelden.',
            'exists' => 'De geselecteerde regio bestaat niet (meer) in de database.',
        ],
        'shipping_size_id' => [
            'required_if' => 'Je hebt niet gekozen voor welke verzendmethode deze verzendkosten moeten gelden.',
            'exists' => 'De geselecteerde verzendmethode bestaat niet (meer) in de database.',
        ],
        'max_weight' => [
            'required_if' => 'Je hebt niet ingevuld voor welk maximaal gewicht deze verzendkosten berekend mogen worden.',
        ],
        'price' => [
            'required' => 'Je hebt geen verzendkosten ingevuld (0 is ook een optie).',
        ],
        'vat_class_id' => [
            'exists' => 'De geselecteerde BTW groep bestaat niet (meer) in de database.',
        ],
        'shipping_vat_class_id' => [
            'required_unless' => 'Je hebt niet gekozen welke BTW groep moet worden toegepast op de verzendkosten.',
            'exists' => 'De geselecteerde BTW groep bestaat niet (meer) in de database.',
        ],
    ],
];
