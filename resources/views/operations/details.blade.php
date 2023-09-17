@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      $crud->entity_name_plural => url($crud->route),
      trans('backpack-shop::order.crud.details') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="container-fluid d-print-none">
        <a href="javascript: window.print();" class="btn float-right"><i class="la la-print"></i></a>
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name !!} {{ __('backpack-shop::order.crud.details') }}: {{ $entry->fancy_invoice_no }}.</span>
            @if ($crud->hasAccess('list'))
                <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="{{ $crud->getShowContentClass() }}">
            <div class="order-details-wrapper">
                <div class="order-details">
                    <table>
                        <tr>
                            <td valign="top">
                                <h1>{{ __('backpack-shop::order.details.title', ['order_no' => $entry->fancy_invoice_no]) }}</h1>
                                <p>&nbsp;</p>
                                <p>
                                    {{ $entry->name }}<br>
                                    {{ $entry->address }}<br>
                                    {{ $entry->zipcode }} {{ $entry->city }}
                                </p>
                            </td>
                            <td valign="top">
                                <h1>{{ env("APP_NAME") }}</h1>
                                <p>&nbsp;</p>
                                <table>
                                    <tr>
                                        <td valign="top">{{ __('backpack-shop::order.details.address') }}:</td>
                                        <td valign-top>
                                            {{ config('eleven59.backpack-shop.address.address') }}<br>
                                            {{ config('eleven59.backpack-shop.address.zipcode') }} {{ config('eleven59.backpack-shop.address.city') }}<br>
                                            {{ config('eleven59.backpack-shop.address.country') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">{{ __('backpack-shop::order.details.phone') }}:</td>
                                        <td valign-top>
                                            {{ config('eleven59.backpack-shop.address.phone') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">{{ __('backpack-shop::order.details.invoice_no') }}:</td>
                                        <td valign-top>{{ $entry->fancy_invoice_no }}</td>
                                    </tr>
                                    <tr>
                                        <td valign="top">{{ __('backpack-shop::order.details.invoice_date') }}:</td>
                                        <td valign-top>{{ $entry->created_at->format('d-m-Y') }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table class="table-order-items">
                                    <tr>
                                        <td><strong>{{ __('backpack-shop::order.details.order_summary') }}</strong></td>
                                        <td align="right"><strong>{{ __('backpack-shop::order.details.excl_vat') }}</strong></td>
                                        <td align="right"><strong>{{ __('backpack-shop::order.details.vat') }}</strong></td>
                                        <td align="right"><strong>{{ __('backpack-shop::order.details.incl_vat') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><hr></td>
                                    </tr>
                                    @foreach($entry->order_summary['products'] as $product)
                                        <tr>
                                            <td>{{ $product['quantity'] }} x {{ $product['description'] }} ({{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($product['price_incl_vat'], 2, ',', '.') }})</td>
                                            <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($product['price_excl_vat'] * $product['quantity'], 2, ',', '.') }}</td>
                                            <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($product['vat'] * $product['quantity'], 2, ',', '.') }}</td>
                                            <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($product['price_incl_vat'] * $product['quantity'], 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4"><hr></td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('backpack-shop::order.details.subtotal') }}</td>
                                        <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($entry->order_summary['totals']['subtotal_excl_vat'], 2, ',', '.') }}</td>
                                        <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($entry->order_summary['totals']['subtotal_incl_vat'] - $entry->order_summary['totals']['subtotal_excl_vat'], 2, ',', '.') }}</td>
                                        <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($entry->order_summary['totals']['subtotal_incl_vat'], 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $entry->order_summary['totals']['shipping_description'] ?? __('backpack-shop::order.details.shipping') . " ({$entry->country})" }}</td>
                                        <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($entry->order_summary['totals']['shipping_excl_vat'], 2, ',', '.') }}</td>
                                        <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($entry->order_summary['totals']['shipping_incl_vat'] - $entry->order_summary['totals']['shipping_excl_vat'], 2, ',', '.') }}</td>
                                        <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($entry->order_summary['totals']['shipping_incl_vat'], 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><hr></td>
                                    </tr>
                                    @if(isset($entry->order_summary['totals']['vat']))
                                        @foreach($entry->order_summary['totals']['vat'] as $vatLine)
                                            <tr>
                                                <td>{{ $vatLine['description'] }}</td>
                                                <td>&nbsp;</td>
                                                <td align="right">{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($vatLine['vat'], 2, ',', '.') }}</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td><strong>{{ __('backpack-shop::order.details.total') }}</strong></td>
                                        <td align="right"><strong>{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($entry->order_summary['totals']['total_excl_vat'], 2, ',', '.') }}</td>
                                        <td align="right"><strong>{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($entry->order_summary['totals']['vat_total'], 2, ',', '.') }}</td>
                                        <td align="right"><strong>{{ config('eleven59.backpack-shop.currency.sign') }} {{ number_format ($entry->order_summary['totals']['total_incl_vat'], 2, ',', '.') }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p>{{ __('backpack-shop::order.details.status', ['status' => $entry->fancy_status]) }}</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after_styles')

    <style>
        .order-details-wrapper {
            max-width: 800px;
            padding: 25px;
        }

        .order-details {
            background: white;
            padding: 10% 8%;
        }

        .order-details * {
            font-family: 'Open Sans', sans-serif;
            font-weight: 400;
            font-size: 12pt;
        }

        .order-details h1 {
            font-size: 18pt;
            font-weight: 400;
        }

        .order-details p,
        .order-details div {
            font-weight: 300;
        }

        .order-details table {
            width: 100%;
        }

        .order-details table.table-order-items {
            margin: 35px 0 75px;
            width: 100%;
        }

        .order-details table td {
            font-weight: 300;
        }

        .order-details strong {
            font-weight: 400;
        }

    </style>
@endpush
