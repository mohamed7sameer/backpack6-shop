<?php

namespace mohamed7sameer\BackpackShop;

use Illuminate\Http\Request;

abstract class PaymentProvider
{

    /**
     * Apart from the functions documented below, extending classes should also include the following constants:
     *
     * protected const STATUS_TRANSLATIONS = [];
     *   required; used to translate payment status from payment method to order status
     *   - key/value pairs need to be "payment_status" => "valid_order_status"
     *   - valid order statuses are "new", "paid" and "cancelled"
     *
     * public const DEPENDENCIES = [];
     *   optional; required if the getPaymentMethods function returns dependencies,
     *             in that case all possible field names need to be defined in this array as well
     */


    /**
     * @param array $options may provide additional options to the payment method
     * @return array of payment methods, which may include dependencies (additional fields to be shown when a method is selected)
     *
     * See https://github.com/mohamed7sameer/backpack6-shop-mollie for an example
     */
    abstract public static function getPaymentMethods(array $options = []) :array;


    /**
     * Create a new payment and return the checkout Url
     *
     * @param array $order_data
     * provided by HandlesOrders Trait in BackpackShop package.
     * the array always contains the following:
     * - (int) order_id
     * - (float) amount
     * - (string) return_url
     * and, if provided/available,
     * - (string) webhook_url
     * - one or more DEPENDENCIES fields (see const above)
     *
     * @return bool|array
     * the function needs to return an array containing the following keys:
     * - an 'id' key, which should contain the payment Id from the payment provider
     * - a 'checkout_url' key, which should contain the url to which the customer should be redirected for checkout
     * - a 'status' key, which should be translatable to an order status
     *   using this class's STATUS_TRANSLATIONS constant (see above)
     *
     * See https://github.com/mohamed7sameer/backpack6-shop-mollie for an example
     */
    abstract public static function createPayment(array $order_data) :bool|array;


    /**
     * This function should retrieve the current payment status from the payment provider using the payment Id
     *
     * @param $payment_id
     * @return bool|string
     */
    abstract public static function getPaymentStatus($payment_id) :bool|string;


    /**
     * This function should return whether or not to consider the order fully processed (i.e., send invoice and emails)
     *
     * @param string $status
     * @return bool
     */
    abstract public static function sendConfirmation(string $status) :bool;


    /**
     * This function needs to process the Webhook data sent from the payment provider, and return the payment Id
     * If the payment provider does not provide this feature, you can just put an empty function that returns 0
     *
     * @param Request $request
     * @return int|string
     */
    abstract public static function getWebhookPaymentId(Request $request) :int|string;


    /**
     * This function should process data the payment provider sends when returning after the payment, and return the payment Id
     *
     * @param Request $request
     * @return int|string
     */
    abstract public static function getResponsePaymentId(Request $request) :int|string;


    /**
     * Construct is to be omitted in the extending classes.
     * This is only here to provide helpful Exceptions for developers if they forget to define the required constants.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        if (!defined('static::STATUS_TRANSLATIONS'))
        {
            throw new \Exception('Constant STATUS_TRANSLATIONS is not defined on ' . get_class($this) . '. This constant is required, because payment processing would fail otherwise. Please add a translation array and make sure all output statuses are either "new", "cancelled", or "paid".');
        }
    }
}
