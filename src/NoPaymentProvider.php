<?php

namespace Eleven59\BackpackShop;

use Eleven59\BackpackShop\Models\Order;
use Illuminate\Http\Request;

class NoPaymentProvider extends \Eleven59\BackpackShopMollie\Models\PaymentProvider
{
    public const STATUS_TRANSLATIONS = ['pending' => 'pending', 'new' => 'new'];
    public const DEPENDENCIES = [];

    /**
     * @param array $options (required by abstract class, not used here)
     * @return array
     */
    public static function getPaymentMethods(array $options = []) :array
    {
        return [
            'nopayment' => [
                'id' => 'nopayment',
                'description' => __('backpack-shop::checkout.payment.no_payment'),
                'dependencies' => [],
            ]
        ];
    }

    /**
     * Create a new Mollie Payment and return the checkout Url
     *
     * @param array $order_data
     * @return bool|array
     */
    public static function createPayment(array $order_data) :bool|array
    {
        return [
            'id' => "{$order_data['order_id']}",
            'status' => 'pending',
            'checkout_url' => '/shopping-cart/no-payment?order_id=' . $order_data['order_id'],
        ];
    }

    /**
     * Retrieve the current payment status
     * (always new)
     *
     * @param $payment_id
     * @return bool|string
     */
    public static function getPaymentStatus($payment_id) :bool|string
    {
        return 'new';
    }


    /**
     * Order fully processed?
     * (always yes)
     *
     * @param string $status
     * @return bool
     */
    public static function sendConfirmation(string $status) :bool
    {
        return true;
    }


    /**
     * Process webhook data and return payment Id
     * (never called for this payment method)
     *
     * @param Request $request
     * @return int|string
     */
    public static function getWebhookPaymentId(Request $request) :int|string
    {
        return false;
    }


    /**
     * Process payment response data and return payment Id
     * (not available for this payment method)
     *
     * @param Request $request
     * @return int|string
     */
    public static function getResponsePaymentId(Request $request) :int|string
    {
        $order_id = $request->get('order');
        if(!$order = Order::find($order_id)) {
            return false;
        }
        return $order->payment_info->id ?? false;
    }
}
