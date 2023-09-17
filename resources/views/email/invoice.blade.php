<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    @include('eleven59.backpack-shop::email.css')

</head>

<body bgcolor="#faf4ef" text="#1A2226" link="#4169E1" vlink="#4169E1" alink="#4169E1" style="margin: 0; font-family: 'Prompt', sans-serif; background-color: #faf4ef; color: #1A2226; font-size: 16px; -webkit-text-size-adjust: 100%; text-size-adjust: 100%; width: 100%;" width="100%">

<div style="padding: 25px 50px;">

    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td>
                @if ($copy)
                    <div style="background-color: #d4edda; border: 1px solid #c3e6cb; padding: 5px 15px; margin-bottom: 35px; color: #155724; text-shadow: none;">
                        <p>{{ __('backpack-shop::invoice.mail.copy') }}</p>
                    </div>
                @endif

                {!! __('backpack-shop::invoice.mail.dear-customer', ['customer' => $order->name]) !!}

                <p style="padding-top: 10px;"><strong>{{ __('backpack-shop::invoice.mail.order_summary') }}</strong></p>

                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    @foreach($order->order_summary['products'] as $product)
                        <tr>
                            <td style="padding-right: 35px; padding-left: 0; padding-bottom: 7px;">{{ $product['quantity'] }} x {{ $product['description'] }} ({{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($product['price_incl_vat'], 2, ',', '.') }})</td>
                            <td style="padding-bottom: 7px; text-align: right; min-width: 60px;" align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($product['price_incl_vat'] * $product['quantity'], 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="border-top: 1px solid #222222; padding-top: 7px; padding-right: 35px; padding-left: 0;"><strong>{{ __('backpack-shop::invoice.pdf.subtotal') }}</strong></td>
                        <td style="border-top: 1px solid #222222; padding-top: 7px; text-align: right; min-width: 60px;" align="right"><strong>{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($order->order_summary['totals']['subtotal_incl_vat'], 2, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 7px; padding-right: 35px; padding-left: 0;"><strong>{{ __('backpack-shop::invoice.pdf.shipping') }}</strong></td>
                        <td style="padding-bottom: 7px; text-align: right; min-width: 60px;" align="right"><strong>{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($order->order_summary['totals']['shipping_incl_vat'], 2, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #222222; padding-top: 7px; padding-bottom: 7px; padding-right: 35px; padding-left: 0; text-align: right;"><strong>{{ __('backpack-shop::invoice.pdf.total') }}</strong></td>
                        <td style="border-top: 1px solid #222222; padding-top: 7px; padding-bottom: 7px; text-align: right; min-width: 60px;" align="right"><strong>{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($order->order_summary['totals']['total_incl_vat'], 2, ',', '.') }}</strong></td>
                    </tr>
                    @if(isset($order->order_summary['totals']['vat']))
                        @foreach($order->order_summary['totals']['vat'] as $vatLine)
                            <tr>
                                <td style="padding-bottom: 7px; padding-right: 35px; padding-left: 0; text-align: right;">{{ $vatLine['description'] }}</td>
                                <td style="padding-bottom: 7px; text-align: right; min-width: 60px;" align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($vatLine['vat'], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>

                {!! __('backpack-shop::invoice.mail.bye-customer', ['store' => config('mail.from.name')]) !!}

            </td>
        </tr>
    </table>

</div>

</body>

</html>
