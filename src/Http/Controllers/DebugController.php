<?php

namespace Eleven59\BackpackShop\Http\Controllers;

use Eleven59\BackpackShop\Mail\Invoice;
use Eleven59\BackpackShop\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\View\View;

class DebugController extends Controller
{
    /**
     * Debug PDF (output PDF as stream to browser for the given order Id)
     * @param Request $request
     * @param $order_id
     * @return mixed
     */
    public function pdf(Request $request, $order_id)
    {
        $order = Order::find($order_id);
        return $order->makePdfInvoice(false);
    }

    /**
     * Debug PDF (output view used for the PDF as html to browser for the given order Id)
     * @param Request $request
     * @param $order_id
     * @return mixed
     */
    public function pdf_html(Request $request, $order_id)
    {
        $order = Order::find($order_id);
        return $order->makePdfInvoice(false, true);
    }

    /**
     * Debug email (manually send confirmations for given order Id)
     * Make sure to only use if using fictitious customer with your own email address, or when having mailtrap
     * or similar service configured for the email server, otherwise the customer's real email address is used
     * @param Request $request
     * @param $order_id
     * @return void
     */
    public function email(Request $request, $order_id)
    {
        $order = Order::find($order_id);
        $invoice = $order->makePdfInvoice();

        // Store owner
        Mail::to(config('mail.from.address'), config('mail.from.name'))->send(new Invoice(['order' => $order, 'copy' => true, 'invoice' => $invoice]));

        // Customer
        Mail::to($order->email, $order->name)->send(new Invoice(['order' => $order, 'copy' => false, 'invoice' => $invoice]));
    }


    /**
     * Debug email template (outputs it as html to the browser for the given order Id)
     * @param Request $request
     * @param $order_id
     * @return void
     */
    public function email_html(Request $request, $order_id) :View
    {
        $order = Order::find($order_id);
        return view(config('eleven59.backpack-shop.invoice-mail-view', 'eleven59.backpack-shop::email.invoice'), [
            'order' => $order,
            'subject' => __('backpack-shop::invoice.mail.title', ['order_no' => $order->fancy_invoice_no]),
            'copy' => false,
        ]);
    }
}
