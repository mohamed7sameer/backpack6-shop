<?php

namespace Eleven59\BackpackShop\Models;

use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\throwException;

class ShoppingCart extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    private $cart;
    public $shippingRules = false;


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        parent::__construct();
        if (empty (session('e59bp-shopping-cart')))
        {
            session([
                'e59bp-shopping-cart' => [
                    'products' => [],
                    'shipping_country' => config('eleven59.backpack-shop.default_shipping_country', 'Netherlands'),
                ],
            ]);
        }
        $this->cart = session('e59bp-shopping-cart');
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS (get/add/update/remove products)
    |--------------------------------------------------------------------------
    */

    /**
     * Add product or variation to cart
     * @param Product $product
     * @param int $quantity
     * @param array $variation
     * @return bool
     */
    public function addItem(Product $product, int $quantity = 1, array $variation = []) :bool
    {
        $key = (string)$product->id;

        if(!empty($variation)) {
            $key .= '-' . $variation['id'];
        }

        if (empty ($this->cart['products'][$key])) {
            $this->cart['products'][$key] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'variation_id' => $variation['id']??'',
            ];
        } else {
            $this->cart['products'][$key]['quantity'] += $quantity;
        }

        return $this->save();
    }

    /**
     * Update quantity of product/variation in cart
     * No addition/substraction; just replaces the quantity with the given number
     * @param Product $product
     * @param int $quantity
     * @param array $variation
     * @return bool
     */
    public function updateQuantity(Product $product, int $quantity = 1, array $variation = []) :bool
    {
        $key = (string)$product->id;

        if(!empty($variation)) {
            $key .= '-' . $variation['id'];
        }

        if (empty ($this->cart['products'][$key])) {
            return false;
        }

        $this->cart['products'][$key]['quantity'] = $quantity;

        return $this->save();
    }

    /**
     * Completely remove a product/variation from the cart
     * Does not subtract 1 from the products in the cart, but completely removes the item, regardless of quantity
     * @param Product $product
     * @param array $variation
     * @return bool
     */
    public function removeItem(Product $product, array $variation = []) :bool
    {
        $key = (string)$product->id;

        if(!empty($variation)) {
            $key .= '-' . $variation['id'];
        }

        unset ($this->cart['products'][$key]);

        return $this->save();
    }

    /**
     * Get products/variations currently in cart
     * @return array
     */
    public function getProductsAttribute() :array
    {
        $this->consolidate();

        $products = [];
        foreach($this->cart['products'] as $index => $_product) {
            $product = Product::find($_product['product_id']);
            $product->quantity = $_product['quantity'];
            $product->variation_id = $_product['variation_id'];
            $products[] = $product;
        }
        return $products;
    }

    /**
     * Get number of products in cart (handy for badge etc)
     * Counts take quantity into account (so 4x the exact same product yields count 4)
     * @return int
     */
    public function getProductCountAttribute() :int
    {
        $count = 0;

        foreach ($this->cart['products'] as $key => $options) {
            $count += $options['quantity'];
        }

        return $count;
    }

    /**
     * Reset the cart content
     * @return bool
     */
    public function empty()
    {
        $this->cart['products'] = [];
        return $this->save();
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS (save to session)
    |--------------------------------------------------------------------------
    */

    /**
     * Save cart to session
     * TODO: add functionality to save cart to DB for logged-in users
     * @param array $options
     * @return bool
     */
    public function save(array $options = []) :bool
    {
        $this->consolidate();
        session(['e59bp-shopping-cart' => $this->cart]);
        return true;
    }

    /**
     * Loops over the cart contents and makes sure all products exist, are valid, etc.
     * Removes all products/variations from cart that have qty < 0, or are no longer available in the DB
     * @return void
     */
    public function consolidate() :void
    {
        $this->shippingRules = false;
        foreach ($this->cart['products'] as $key => $options) {

            // Delete item from cart if quantity is lower than 1
            if($options['quantity'] < 1) {
                unset ($this->cart['products'][$key]);
                continue;
            }

            // Check if product still exists
            if (!$product = Product::find($options['product_id'])) {
                unset ($this->cart['products'][$key]);
                continue;
            }

            // Remove if product is not eligible for order
            if (!$product->product_status->sales_allowed) {
                unset ($this->cart['products'][$key]);
                continue;
            }

            // No variation, product still exists, leave in cart
            if($key === $options['product_id']) {
                continue;
            }

            // Check variations; delete item from cart if no variation matches the index
            $exists = false;
            foreach($product->variations as $variation) {
                if($key === "{$options['product_id']}-{$variation['id']}") {
                    $exists = true;
                    break;
                }
            }
            if(!$exists) {
                unset ($this->cart['products'][$key]);
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS (shipping)
    |--------------------------------------------------------------------------
    */

    /**
     * Store address info in session so users don't have to re-enter when going back to the info form
     * @param array $values
     * @return bool
     */
    public function setAddress(array $values) :bool
    {
        $this->cart['address'] = $values;
        return $this->save();
    }

    /**
     * Get address info to display in cart
     * @param string $countryCommonName
     * @return bool
     */
    public function getAddress(string $key) :string
    {
        return $this->cart['address'][$key] ?? '';
    }

    /**
     * Get address info to display in cart
     * @param string $countryCommonName
     * @return bool
     */
    public function getShippingDescription() :string
    {
        $description = 'Shipping';

        if(!($this->shippingRules || $this->loadShippingRules())) {
            return $description;
        }

        if(isset($this->cart['address']['country'])) {
            $description = 'Shipping to ' . $this->cart['address']['country'];
            if(bpshop_shipping_size_enabled()) {
                $description .= ' (' . $this->shippingRules[0]->shipping_size->name . ')';
            }
        }
        return $description;
    }

    /**
     * Update the shipping country for calculating the shipping fee
     * Checks whether the country belongs to a region that has at least one valid shipping rule
     * @param string $countryCommonName
     * @return bool
     */
    public function setShippingCountry(string $countryCommonName = 'Netherlands') :bool
    {
        if(!in_array($countryCommonName, bpshop_shipping_countries())) {
            // Non-shippable country
            return false;
        }
        $this->cart['shipping_country'] = $countryCommonName;
        return $this->save();
    }

    /**
     * Dedicated function to load available shipping methods for the current basket
     * Prevents clutter as well as duplicate operations
     * @return bool
     */
    public function loadShippingRules() :bool
    {
        $shippingSizes = null;
        $shippingWeight = 0;
        foreach($this->cart['products'] as $cartProduct) {
            $productModel = Product::find($cartProduct['product_id']);
            if(bpshop_shipping_size_enabled()) {
                $productShippingSizes = $productModel->getShippingSizes($cartProduct['quantity']);
                $productShippingSizeIds = [];
                foreach($productShippingSizes as $productShippingSize) {
                    $productShippingSizeIds[] = $productShippingSize->id;
                }
                $shippingSizes = isset($shippingSizes) ? array_intersect($shippingSizes, $productShippingSizeIds) : $productShippingSizeIds;
            }
            if(bpshop_shipping_weight_enabled()) {
                $shippingWeight += $productModel->shipping_weight * $cartProduct['quantity'];
            }
        }

        $shippingRegion = ShippingRegion::getByCountry($this->cart['shipping_country']);
        if(empty($shippingRegion)) {
            return false;
        }

        $q = ShippingRule::where('shipping_region_id', $shippingRegion->id)->orderBy('price', 'asc');
        if(bpshop_shipping_size_enabled()) {
            $q->whereIn('shipping_size_id', $shippingSizes);
        }
        if(bpshop_shipping_weight_enabled()) {
            $q->where('max_weight', '>=', $shippingWeight);
        }
        $compatibleShippingRules = $q->get();

        if(!count($compatibleShippingRules)) {
            return false;
        }

        $this->shippingRules = $compatibleShippingRules;
        return true;
    }

    /**
     * Function that calls the appropriate paymentprovider's getPaymentMethods, so this logic is not required in template files
     * @return mixed
     */
    public function getPaymentMethods()
    {
        $classname = config('eleven59.backpack-shop.payment_provider', null);
        $paymentProvider = new $classname();
        return $paymentProvider->getPaymentMethods();
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS (totals and calculations)
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate and return the subtotal, including VAT, excluding shipping
     * @return float
     */
    public function getSubtotalInclVatAttribute() :float
    {
        $price = 0;

        foreach ($this->cart['products'] as $cartProduct) {
            $productModel = Product::find($cartProduct['product_id']);
            $productPrice = $productModel->getSalesPriceInclVat($cartProduct['variation_id']);
            $price += $productPrice * $cartProduct['quantity'];
        }

        return $price;
    }

    /**
     * Calculate and return the subtotal, excluding VAT and shipping
     * @return float
     */
    public function getSubtotalExclVatAttribute() :float
    {
        $exclVat = 0;
        foreach($this->cart['products'] as $product) {
            $productModel = Product::find($product['product_id']);
            $exclVat += ($productModel->getSalesPriceExclVat($product['variation_id']) * $product['quantity']);
        }
        return $exclVat;
    }

    /**
     * Calculate and return the total VAT based on the subtotal
     * @return float
     */
    public function getVatSubtotalAttribute() :float
    {
        $vat = 0;
        foreach($this->cart['products'] as $product) {
            $productModel = Product::find($product['product_id']);
            $vat += ($productModel->getSalesVat($product['variation_id']) * $product['quantity']);
        }
        return $vat;
    }

    /**
     * Calculate and return the shipping fee, including VAT
     * @return float
     */
    public function getShippingPriceInclVatAttribute() :bool|float
    {
        if(!($this->shippingRules || $this->loadShippingRules())) {
            return false;
        }
        return $this->shippingRules[0]->getPriceInclVat();
    }

    /**
     * Calculate and return the shipping fee, excluding VAT
     * @return float
     */
    public function getShippingPriceExclVatAttribute() :bool|float
    {
        if(!($this->shippingRules || $this->loadShippingRules())) {
            return false;
        }
        return $this->shippingRules[0]->getPriceExclVat();
    }

    /**
     * Calculate and return the VAT based on the shipping fee
     * @return float
     */
    public function getShippingVatAttribute() :bool|float
    {
        if(!($this->shippingRules || $this->loadShippingRules())) {
            return false;
        }
        return $this->shippingRules[0]->getVat();
    }

    /**
     * Calculate and return the total, including VAT and shipping fee
     * @return float
     */
    public function getTotalInclVatAttribute() :float
    {
        return $this->subtotal_incl_vat + $this->shipping_price_incl_vat;
    }

    /**
     * Calculate and return the total, excluding VAT, including shipping fee
     * @return float
     */
    public function getTotalExclVatAttribute() :float
    {
        return $this->subtotal_excl_vat + $this->shipping_price_excl_vat;
    }

    /**
     * Calculate and return the total amount of VAT for the order
     * @return float
     */
    public function getVatTotalAttribute() :float
    {
        return $this->vat_subtotal + $this->shipping_vat;
    }

    /**
     * Produce an array of VAT divided for the possible different VAT Classes
     * @return float
     */
    public function getVatSummaryAttribute() :array
    {
        $vat_classes = [];
        foreach ($this->cart['products'] as $cartProduct) {
            $productModel = Product::find($cartProduct['product_id']);
            if(!isset($vat_classes[$productModel->vat_class->id])) {
                $vat_classes[$productModel->vat_class->id] = [
                    'description' => $productModel->vat_class->name,
                    'vat' => 0
                ];
            }
            $vat_classes[$productModel->vat_class->id]['vat'] +=
                $productModel->getSalesVat($cartProduct['variation_id']) * $cartProduct['quantity'];
        }

        if(($this->shippingRules || $this->loadShippingRules())) {
            $shipping_rule = $this->shippingRules[0];
            if(!isset($vat_classes[$shipping_rule->shipping_vat_class->id])) {
                $vat_classes[$shipping_rule->shipping_vat_class->id] = [
                    'description' => $shipping_rule->shipping_vat_class->name,
                    'vat' => 0
                ];
            }
            $vat_classes[$shipping_rule->shipping_vat_class->id]['vat'] += $shipping_rule->getVat();
        }

        return $vat_classes;
    }

    /**
     * Returns an array neatly including all of the above
     * This is probably the function you want to load into a variable for easy access in your views
     * @return array
     */
    public function getTotalsAttribute() :array
    {
        return [
            'subtotal_incl_vat' => $this->subtotal_incl_vat,
            'subtotal_excl_vat' => $this->subtotal_excl_vat,
            'vat_subtotal' => $this->vat_subtotal,
            'shipping_incl_vat' => $this->shipping_price_incl_vat,
            'shipping_excl_vat' => $this->shipping_price_excl_vat,
            'shipping_vat' => $this->shipping_vat,
            'shipping_description' => $this->getShippingDescription(),
            'total_incl_vat' => $this->total_incl_vat,
            'total_excl_vat' => $this->total_excl_vat,
            'vat_total' => $this->vat_total,
            'vat' => $this->vat_summary,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS (order)
    |--------------------------------------------------------------------------
    */

    /**
     * Generate an order summary to pass on to the Order object and save to the DB
     * @return array
     */
    public function getOrderSummary() :array
    {
        $orderSummary = [
            'products' => [],
            'totals' => $this->totals,
        ];

        foreach($this->cart['products'] as $cartProduct)
        {
            $productModel = Product::find($cartProduct['product_id']);
            $orderSummary['products'][] = [
                'description' => $productModel->name,
                'variation' => $productModel->getVariationSummary($cartProduct['variation_id'] ?? null),
                'quantity' => $cartProduct['quantity'],
                'price_incl_vat' => $productModel->getSalesPriceInclVat($cartProduct['variation_id']),
                'price_excl_vat' => $productModel->getSalesPriceExclVat($cartProduct['variation_id']),
                'vat' => $productModel->getSalesVat($cartProduct['variation_id']),
            ];
        }

        return $orderSummary;
    }
}
