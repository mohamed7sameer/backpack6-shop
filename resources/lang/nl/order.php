<?php

return [

    /**
     * Description for payment
     */
    'payment-description' => 'Je bestelling',
    'error-processing' => 'Er ging iets mis met je bestelling. Probeer het opnieuw, of neem contact met ons op als dit blijft gebeuren.',

    /**
     * Statuses
     */
    'statuses' => [
        'paid' => 'Betaald',
        'cancelled' => 'Geannuleerd',
        'new' => 'Nieuw',
    ],
    'status-messages' => [
        'paid' => 'Bedankt voor je bestelling. Je hebt een bevestigingsmail ontvangen. Je ontvangt nog een bevestiging wanneer je bestelling is verzonden.',
        'new' => 'Je bestelling is gelukt, maar de betaling is nog niet volledig verwerkt. Zodra je betaling alsnog verwerkt is (meestal automatisch, en binnen een paar uur) ontvang je de orderbevestiging per email. Als het langer dan een paar uur duurt, neem dan contact met ons op.',
        'cancelled' => 'Volgens onze betaalpartner is je betaling niet gelukt of niet afgerond. De bestelling is daarom ook geannulleerd. De inhoud van je winkelwagen is bewaard, dus mocht dit een foutje zijn dan kun je eenvoudig nog een keer opnieuw proberen de bestelling te plaatsen.',
        'error' => 'Er ging iets mis bij je betaling. Neem contact met ons op om te achterhalen wat er mis ging, mocht er toch geld van je rekening zijn afgeschreven. Stuur dit bericht mee wanneer je contact met ons opneemt.',
    ],

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'bestelling',
        'plural' => 'bestellingen',
        'details' => 'Details',
        'payment-description' => 'Bedankt voor je bestelling bij :store_name',
        'created_at' => [
            'label' => 'Besteld',
        ],
        'order_no' => [
            'label' => 'Order nr.',
        ],
        'full_address' => [
            'label' => 'Adres',
        ],
        'order_items' => [
            'label' => 'Producten',
        ],
        'order_total' => [
            'label' => 'Totaal',
        ],
        'status' => [
            'label' => 'Status',
        ],
    ],

    /**
     * Details
     */
    'details' => [
        'title' => 'Bestelling (:order_no)',
        'phone' => 'Telefoon',
        'address' => 'Adres',
        'invoice_no' => 'Factuurnr.',
        'invoice_date' => 'Datum',
        'order_summary' => 'Samenvatting',
        'excl_vat' => 'excl. BTW',
        'vat' => 'BTW',
        'incl_vat' => 'incl. BTW',
        'subtotal' => 'Subtotaal',
        'shipping' => 'Verzendkosten',
        'total' => 'Totaal',
        'status' => 'Status van de bestelling: :status',
    ],
];
