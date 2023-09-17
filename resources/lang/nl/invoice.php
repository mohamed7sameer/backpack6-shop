<?php

return [

    /**
     * Mail
     */
    'mail' => [
        'title' => 'Je bestelling (:order_no)',
        'title_copy' => 'Nieuwe bestelling (:order_no)',
        'subject' => 'Factuur',
        'copy' => 'Dit is een kopie van de e-mail die naar de klant is verzonden.',
        'dear-customer' => "<p>Beste :customer,<br>&nbsp;<br>Bedankt voor je bestelling. Deze e-mail bevestigt dat de bestelling succesvol is geplaatst.</p>",
        'bye-customer' => "<p>Nogmaals bedankt. We hopen dat je heel veel plezier hebt van je bestelling.<br>&nbsp;<br>Hartelijke groeten,<br>&nbsp;<br>:store</p>",
        'order_summary' => 'Samenvatting',
        'subtotal' => 'Subtotaal',
        'shipping' => 'Verzendkosten',
        'total' => 'Totaal',
    ],

    /**
     * PDF
     */
    'pdf' => [
        'title' => 'Je bestelling (:order_no)',
        'title_copy' => 'Nieuwe bestelling (:order_no)',
        'phone' => 'Telefoon',
        'address' => 'Adres',
        'invoice_no' => 'Factuurnr.',
        'invoice_date' => 'Datum',
        'order_summary' => 'Samenvatting',
        'subtotal' => 'Subtotaal',
        'shipping' => 'Verzendkosten',
        'total' => 'Totaal',
        'thanks' => 'Bedankt voor je bestelling',
    ],
];
