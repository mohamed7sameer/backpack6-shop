<?php

namespace Eleven59\BackpackShop\Http\Controllers;

use Eleven59\BackpackShop\Http\Requests\CheckoutRequest;
use Eleven59\BackpackShop\Http\Requests\OrderRequest;
use Eleven59\BackpackShop\Traits\HandlesOrders;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class OrderController extends Controller
{
    use HandlesOrders;

    /**
     * Process email and address information, save to cart, and redirect to the next checkout step (shipping + payment)
     * @param CheckoutRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function processCheckout(CheckoutRequest $request)
    {
        shoppingcart()->setAddress($request->all());
        shoppingcart()->setShippingCountry($request->get('country'));

        return redirect($request->get('redirect_url'));
    }

    /**
     * Process payment method and sent user to checkout URL provided by the payment provider
     * @param OrderRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function placeOrder(OrderRequest $request) :RedirectResponse
    {
        if($checkoutUrl = $this->processOrder($request)) {
            return Redirect::to($checkoutUrl);
        }
        return Redirect::back()->withErrors(['msg' => __('backpack-shop::order.error-processing')]);
    }

    /**
     * Helper function to immediately show thanks page for the NoPayment provider
     * @param Request $request
     * @return View
     * @throws \Exception
     */
    public function noPayment(Request $request) :View
    {
        $this->updatePayment($request->get('order_id'));
        shoppingcart()->empty();
        return view(config('eleven59.backpack-shop.payment-return-view'), [
            'payment_result' => [
                'status' => 'new',
                'msg' => __('backpack-shop::order.status-messages.new'),
            ],
        ]);
    }

    /**
     * Process the result of the payment after user returns from the payment provider
     * @param Request $request
     * @return View
     * @throws \Exception
     */
    public function paymentResult(Request $request) :View
    {
        $paymentProvider = $this->getPaymentProvider();
        if(!$paymentId = $paymentProvider->getResponsePaymentId($request)) {
            $status = 'error';
            $msg = "It seems you have reached this page in an error. Please contact us if you expected something else to happen.";
        } else {
            $status = $this->updatePayment($paymentId) ?? 'error';
            $msg = __('backpack-shop::order.status-messages.'.$status) ?? "Unknown status, please contact us if you expected something else";

            if($status === 'paid' || $status === 'new') {
                shoppingcart()->empty();
            }
        }

        return view(config('eleven59.backpack-shop.payment-return-view'), [
            'payment_result' => [
                'status' => $status,
                'msg' => $msg,
            ],
        ]);
    }

    /**
     * Helper function to connect the payment provider webhook to this package
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function webhook(Request $request)
    {
        $paymentProvider = $this->getPaymentProvider();
        $paymentId = $paymentProvider->getWebhookPaymentId($request);
        $status = $this->updatePayment($paymentId) ?? 'error';

        if($status !== 'error') {
            return response("OK");
        }
        return response("NOT OK");
    }
}
