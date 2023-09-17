<?php

return [

    /*
     * Address for invoices
     */
    'address' => [
        'address' => 'Examplestreet 1',
        'zipcode' => '6666 AA',
        'city' => 'Emptyville',
        'country' => 'Netherlands',
        'phone' => '+31 10 12 34 567',
    ],

    /*
     * Currency (only one currency supported at this time)
     */
    'currency' => [
        'sign' => '€',
        'abbreviation' => 'EUR',
        'full' => 'Euro',
    ],

    /*
     * Enter prices including or excluding VAT
     */
    'prices_include_vat' => true,

    /*
     * Shipping calculation method
     *
     * Valid values:
     *
     * weight: shipping is calculated using the cheapest shipping option that matches
     *         the total weight in the cart for the selected shipping region.
     * size: shipping is calculated using the biggest (most expensive) package size
     *         for the selected shipping region.
     * both: shipping is calculated using the cheapest shipping option that matches both
     *         the biggest (most expensive) package size and minimum weight for the
     *         selected shipping region.
     *
     * Defaults to 'both' when no valid option is selected.
     */
    'shipping-calculation' => 'both',

    /*
     * Default shipping country
     * - Needs to be mapped to a shipping rule in the backend before this works
     */
    'default_shipping_country' => 'Netherlands',

    /*
     * Payment provider to use
     * see documentation, https://github.com/mohamed7sameer/backpack6-shop
     * example, see: https://github.com/mohamed7sameer/backpack6-shop-mollie
     * or write your own (see abstract class and the example above
     */
    'payment_provider' => \mohamed7sameer\BackpackShop\NoPaymentProvider::class,
//    'payment_provider' => \mohamed7sameer\BackpackShopMollie\Models\PaymentProvider::class,

    /*
     * Description for payments
     * The default (null) uses a translation string in which the store name is replaced
     * Find it in the "order.php" translation file under key "payment-description"
     * Publish that file to override, or override here
     */
    'payment-description' => null,

    /*
     * View to use when returning from payment provider
     * Provides the $payment_result variable, which contains:
     * $payment_result['status'] for the order status
     * $payment_result['msg'] for the translated message to go with that status
     * (see Order class and order.php translation file)
     * Note: this needs to be a view that can run with no additional variables provided
     */
    'payment-return-view' => 'mohamed7sameer.backpack-shop::frontend.payment-return',

    /*
     * Invoice no. format
     * Available variables (case-sensitive):
     * :year
     * :number
     */
    'invoice_no_format' => "W:year:number",

    /*
     * Length of the invoice no (0 = don't pad)
     * Example: if the pad length is 4 (default), invoice no. 1 will become 0001 in the format defined above
     */
    'invoice_no_pad_len' => 4,

    /*
     * View to use for the invoice PDF
     * This package provides a generic template that should be fine in most cases
     * But you may wish to adapt the style to the look and feel of the website
     */
    'invoice-pdf-view' => 'mohamed7sameer.backpack-shop::pdf.invoice',

    /*
     * View to use for the order confirmation email
     * This package provides a generic template that should be fine in most cases
     * But you may wish to adapt the style to the look and feel of the website
     */
    'invoice-mail-view' => 'mohamed7sameer.backpack-shop::email.invoice',

    /*
     * Hide or show slug fields in admin CRUD panel
     * They will always be populated, but this setting controls whether the user is free to edit them
     */
    'hide-slugs' => false,

    /*
     * Override upload fields (default crud image field settings)
     * See https://backpackforlaravel.com/docs/5.x/crud-fields#image-pro
     */
    'category-cover' => [
        'aspect-ratio' => 0,
        'crop' => true,
//        'disk' => null,
//        'prefix' => null,
    ],
    'category-meta-image' => [
        'aspect-ratio' => 1.91,
        'crop' => true,
//        'disk' => null,
//        'prefix' => null,
    ],
    'product-cover' => [
        'aspect-ratio' => 0,
        'crop' => true,
//        'disk' => null,
//        'prefix' => null,
    ],
    'product-photos' => [
        'aspect-ratio' => 0,
        'crop' => true,
//        'disk' => null,
//        'prefix' => null,
    ],
    'product-meta-image' => [
        'aspect-ratio' => 1.91,
        'crop' => true,
//        'disk' => null,
//        'prefix' => null,
    ],
    'product-variation-image' => [
        'aspect-ratio' => 0,
        'crop' => true,
//        'disk' => null,
//        'prefix' => null,
    ],

    /*
     * Non-standard fields to be displayed in the CRUD admin area for products
     * Accepts full backpack crud field definitions, including 'tab' (default is a separate tab named 'Features'),
     * but remember you can't use translations, model functions, or complex definitions in config files.
     *
     * Additionally, use 'beforeField' or 'afterField' to place the field before or after a specific field
     * (use column name, or "features[name]").
     *
     * These can be retrieved using $productModel->extras['field_name']
     */
    'product_extras' => [
//        'brand' =>   [
//            'label'       => "Brand",
//            'tab'         => "Info",
//            'type'        => 'text',
//            'afterField'  => 'slug',
//        ],
    ],

    /*
     * Add column names below to hide standard product attributes in Backpack admin panel
     * (see products table schema; but some can't be removed because they are needed for functionality)
     */
    'hide-product-columns' => [
//        'variations',
    ],

    /*
     * Add column names below to hide standard category attributes in Backpack admin panel
     * (see categories table schema; but some can't be removed because they are needed for functionality)
     */
    'hide-product-category-columns' => [
//        'cover',
    ],

    /*
     * Add column names below to hide standard shipping rule attributes in Backpack admin panel
     * (see categories table schema; but some can't be removed because they are needed for functionality)
     */
    'hide-shipping-rule-columns' => [
//        'vat_class_id',
    ],
];
