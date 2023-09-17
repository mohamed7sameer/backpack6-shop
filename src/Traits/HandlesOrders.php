<?php

namespace Eleven59\BackpackShop\Traits;

use Eleven59\BackpackShop\Http\Requests\OrderRequest;
use Eleven59\BackpackShop\Mail\Invoice;
use Eleven59\BackpackShop\Models\Order;
use Eleven59\BackpackShop\PaymentProvider;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

trait HandlesOrders
{
    /**
     * Classes implementing this trait should contain the following functions
     */
    abstract public function placeOrder(OrderRequest $request) :RedirectResponse;

    /**
     * Helper function to process the submitted order.
     * Returns false or a payment checkout Url to redirect to.
     * @param OrderRequest $request
     * @param $status
     * @return false
     * @throws Exception
     */
    protected function processOrder(OrderRequest $request)
    {
        $paymentProvider = $this->getPaymentProvider();

        $order = new Order();
        $orderSummary = shoppingcart()->getOrderSummary();
        $order->fill([
            'order_summary' => $orderSummary,
            'name' => $request->get('name', shoppingcart()->getAddress('name')),
            'email' => $request->get('email', shoppingcart()->getAddress('email')),
            'phone' => $request->get('phone', shoppingcart()->getAddress('phone')),
            'address' => $request->get('address', shoppingcart()->getAddress('address')),
            'zipcode' => $request->get('zipcode', shoppingcart()->getAddress('zipcode')),
            'city' => $request->get('city', shoppingcart()->getAddress('city')),
            'country' => $request->get('country', shoppingcart()->getAddress('country')),
            'state' => $request->get('state', shoppingcart()->getAddress('state')),
            'remarks' => $request->get('remarks', null),
            'status' => 'new',
            'payment_info' => null,
            'invoice_year' => null,
            'invoice_no' => null,
        ])->save();

        $payment = [
            'order_id' => $order->id,
            'amount' => (float)$orderSummary['totals']['total_incl_vat'],
            'return_url' => url('/payment-result') . '?order=' . $order->id,
            'payment_method' => $request->get('payment_method'),
        ];

        $dependencies = $paymentProvider::DEPENDENCIES ?? [];
        foreach($paymentProvider::DEPENDENCIES as $name) {
            if($value = $request->get($name, false)) {
                $payment[$name] = $value;
            }
        }

        if($result = $paymentProvider::createPayment($payment)) {
            $order_status = $paymentProvider::STATUS_TRANSLATIONS[$result['status']];
            $order->fill([
                'payment_info' => $result,
                'status' => $order_status,
            ])->save();
            return $result['checkout_url'];
        }

        return false;
    }

    /**
     * Helper function to update the payment
     * Returns false or a new payment status to process
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    protected function processPayment(Request $request) :bool
    {
        $order_id = $request->get('id');
        if(!$order = Order::find($order_id) || !isset($order->payment_info['id'])) {
            return false;
        }

        return $this->updatePayment($order->payment_info['id']);
    }

    /**
     * Function to update the payment status either on returning from payment or through payment method webhooks
     * If the new status is different from the old one, and the new one is a final state (i.e., order is paid),
     * This also generates the final invoice ID and PDF, and sends out email confirmations to both customer and shop owner
     * @param $payment_id
     * @return string containing the new order status
     * @throws Exception
     */
    protected function updatePayment($payment_id) :string
    {
        $paymentProvider = $this->getPaymentProvider();

        if(!$order = Order::getByPaymentId($payment_id)) {
            throw new Exception("The payment can not be processed because there is no order for this payment Id in the database.");
        }
        $old_order_status = $order->status;

        $payment_status = $paymentProvider::getPaymentStatus($payment_id);
        $new_order_status = $paymentProvider::STATUS_TRANSLATIONS[$payment_status];

        $payment_info = $order->payment_info;
        $payment_info->status = $payment_status;
        $order->fill([
            'payment_info' => $payment_info,
            'status' => $new_order_status,
        ])->save();

        if($old_order_status !== $new_order_status)
        {
            if ($paymentProvider::sendConfirmation($new_order_status))
            {
                $order->invoice_year = (int) date('Y');
                $order->invoice_no = Order::getNextInvoiceNo();
                $order->save();

                $invoice = $order->makePdfInvoice();

                // Store owner
                Mail::to(config('mail.from.address'), config('mail.from.name'))->send(new Invoice(['order' => $order, 'copy' => true, 'invoice' => $invoice]));

                // Customer
                Mail::to($order->email, $order->name)->send(new Invoice(['order' => $order, 'copy' => false, 'invoice' => $invoice]));
            }
        }

        return $order->status;
    }

    /**
     * Helper function that returns the PaymentProvider class that is configured in config/backpack-shop.php
     * @return mixed
     * @throws Exception
     */
    public function getPaymentProvider()
    {
        $paymentProviderClass = config('eleven59.backpack-shop.payment_provider', null);
        if(empty($paymentProviderClass)) {
            throw new Exception("The payment can not be processed because there is no payment provider configured. Please add a valid payment provider (see backpack-shop config).");
        }
        $paymentProvider = new $paymentProviderClass();
        if(!is_subclass_of($paymentProvider::class, PaymentProvider::class)) {
            throw new Exception("The payment can not be processed because the payment provider in the config file is not a valid subclass of the abstract class Eleven59\BackpackShop\PaymentProvider.");
        }

        return $paymentProvider;
    }
}
