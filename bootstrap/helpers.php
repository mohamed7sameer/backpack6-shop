<?php

if(!function_exists('shoppingcart')) {
    /**
     * Global shoppingcart access
     * @return \mohamed7sameer\BackpackShop\Models\ShoppingCart
     */
    function shoppingcart()
    {
        return new \mohamed7sameer\BackpackShop\Models\ShoppingCart();
    }
}

if (!function_exists('bpshop_show_column')) {
    /**
     * Helper function for CRUD controllers to prevent clutter
     * @param string $model
     * @param string $column
     * @return bool
     */
    function bpshop_show_column(string $model, string $column) :bool {
        return !in_array($column, config("mohamed7sameer.backpack-shop.hide-{$model}-columns", []));
    }
}

if (!function_exists('bpshop_mapped_countries')) {
    /**
     * Helper function to show the available countries for shipping rules that have not yet been mapped to an existing shipping rule
     * @return array
     */
    function bpshop_mapped_countries() :array {
        // Make sure we only report countries that actually exist
        $countries = [];
        // $allCountries = \Mohamed7sameer\Countries\Package\Countries::all()->pluck("name.common", "name.common")->toArray();
        $allCountries = Symfony\Component\Intl\Countries::getNames();

        $regions = \mohamed7sameer\BackpackShop\Models\ShippingRegion::all();





        foreach ($regions as $region) {
            foreach ($region->countries as $country) {
                if (isset($allCountries[$country])) {
                    $countries[$country] = $country;
                }
            }
        }
        return $countries;
    }
}

if (!function_exists('bpshop_country_filters')) {
    /**
     * Helper function to show the available countries for shipping rules
     * @return array
     */
    function bpshop_country_filters() :array {
        $countries = bpshop_mapped_countries();
        $filters = [];
        foreach ($countries as $key => $country) {
            $filters[] = bpshop_country_slug($country);
        }
        return $filters;
    }
}

if (!function_exists('bpshop_shipping_countries')) {
    /**
     * Helper function to show only the countries that are linked to a valid shipping rule (useful for checkout form)
     * @return array
     */
    function bpshop_shipping_countries() {
        $shipping_countries = [];
        $countries = \Mohamed7sameer\Countries\Package\Countries::all()->pluck("name.common", "name.common")->toArray();
        $regions = \mohamed7sameer\BackpackShop\Models\ShippingRegion::has('shipping_rule')->get();
        foreach ($regions as $region) {
            foreach ($region->countries as $country) {
                if (isset($countries[$country])) {
                    $shipping_countries[$country] = $country;
                }
            }
        }
        ksort($shipping_countries);
        return $shipping_countries;
    }
}

if (!function_exists('bpshop_shipping_size_enabled')) {
    /**
     * Shorthand helper to prevent clutter
     * @return bool
     */
    function bpshop_shipping_size_enabled(): bool
    {
        return in_array(strtolower(config('mohamed7sameer.backpack-shop.shipping-calculation', 'both')), ['both', 'size']);
    }
}

if (!function_exists('bpshop_shipping_weight_enabled')) {
    /**
     * Shorthand helper to prevent clutter
     * @return bool
     */
    function bpshop_shipping_weight_enabled(): bool
    {
        return in_array(strtolower(config('mohamed7sameer.backpack-shop.shipping-calculation', 'both')), ['both', 'weight']);
    }
}

if (!function_exists('bpshop_country_slug')) {
    /**
     * Shorthand helper to prevent clutter
     * @return bool
     */
    function bpshop_country_slug(string $country): string
    {
        return preg_replace("/[^A-Za-z0-9]/", '', strtolower($country));
    }
}
