<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('backpack-shop::invoice.pdf.title') }}</title>
    @include('eleven59.backpack-shop::pdf.css')
</head>

<body>
<main>
    <table>
        <tr>
            <td valign="top">
                <h1>{{ __('backpack-shop::invoice.pdf.title') }}</h1>
                <p>&nbsp;</p>
                <p>
                    {{ $order->name }}<br>
                    {{ $order->address }}<br>
                    {{ $order->zipcode }} {{ $order->city }}
                </p>
            </td>
            <td valign="top">
                <h1>{{ env("APP_NAME") }}</h1>
                <p>&nbsp;</p>
                <table>
                    <tr>
                        <td valign="top">{{ __('backpack-shop::invoice.pdf.address') }}:</td>
                        <td valign-top>
                            {{ config('eleven59.backpack-shop.address.address') }}<br>
                            {{ config('eleven59.backpack-shop.address.zipcode') }} {{ config('eleven59.backpack-shop.address.city') }}<br>
                            {{ config('eleven59.backpack-shop.address.country') }}
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">{{ __('backpack-shop::invoice.pdf.phone') }}:</td>
                        <td valign-top>
                            {{ config('eleven59.backpack-shop.address.phone') }}
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">{{ __('backpack-shop::invoice.pdf.invoice_no') }}:</td>
                        <td valign-top>{{ $order->fancy_invoice_no }}</td>
                    </tr>
                    <tr>
                        <td valign="top">{{ __('backpack-shop::invoice.pdf.invoice_date') }}:</td>
                        <td valign-top>{{ $order->created_at->format('d-m-Y') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="table-order-items">
                    <tr>
                        <td colspan="2"><strong>{{ __('backpack-shop::invoice.pdf.order_summary') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
                    @foreach($order->order_summary['products'] as $product)
                        <tr>
                            <td>{{ $product['quantity'] }} x {{ $product['description'] }} ({{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($product['price_incl_vat'], 2, ',', '.') }})</td>
                            <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($product['price_incl_vat'] * $product['quantity'], 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
                    <tr>
                        <td>{{ __('backpack-shop::invoice.pdf.subtotal') }}</td>
                        <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($order->order_summary['totals']['subtotal_incl_vat'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('backpack-shop::invoice.pdf.shipping') }}</td>
                        <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($order->order_summary['totals']['shipping_incl_vat'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('backpack-shop::invoice.pdf.total') }}</strong></td>
                        <td align="right"><strong>{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($order->order_summary['totals']['total_incl_vat'], 2, ',', '.') }}</td>
                    </tr>
                    @if(isset($order->order_summary['totals']['vat']))
                        @foreach($order->order_summary['totals']['vat'] as $vatLine)
                            <tr>
                                <td>{{ $vatLine['description'] }}</td>
                                <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($vatLine['vat'], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p>{{ __('backpack-shop::invoice.pdf.thanks') }}</p>
            </td>
        </tr>
    </table>
</main>
</body>

</html>
