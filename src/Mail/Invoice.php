<?php

namespace Eleven59\BackpackShop\Mail;

use Eleven59\BackpackShop\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Invoice extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject, $order, $copy, $invoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($params = [])
    {
        $this->order = Order::find($params['order']->id);
        $this->copy = $params['copy'];
        $this->subject = __('backpack-shop::invoice.mail.title', ['order_no' => $this->order->fancy_invoice_no]);
        if($this->copy) {
            $this->subject = __('backpack-shop::invoice.mail.title_copy', ['order_no' => $this->order->fancy_invoice_no]);
        }
        $this->invoice = $params['invoice'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo($this->copy ? $this->order->email : config('mail.from.address'), $this->copy ? $this->order->name : config('mail.from.name'))
            ->subject($this->subject)
            ->view(config('eleven59.backpack-shop.invoice-mail-view', 'backpack-shop::email.invoice'))
            ->attach($this->invoice);
    }
}
