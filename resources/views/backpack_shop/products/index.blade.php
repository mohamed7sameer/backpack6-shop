@extends('mohamed7sameer.backpack-shop::backpack_shop.layout.main')


@section('content')
    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Products</h2>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">

                @php($currencySign = config('mohamed7sameer.backpack-shop.currency.sign'))
                @forelse ($products as $product)
                    <div class="group relative">
                        <div
                            class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                            <img src="{{ Storage::disk('public')->url($product->cover) }}" alt=""
                                class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="{{ route('bs-product-show', $product->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $product->name }}
                                    </a>
                                </h3>
                            </div>
                            <p class="text-sm font-medium text-gray-900">
                                @if ($product->sale_price)
                                    <span class="text-base font-normal text-gray-500 line-through dark:text-gray-400">
                                        {{ $currencySign }} {{ number_format($product->sale_price, 2, ',', '') }}
                                    </span>
                                @endif
                                <span>
                                    {{ $currencySign }} {{ number_format($product->price, 2, ',', '') }}
                                </span>

                            </p>
                        </div>
                    </div>


                @empty
                    <div class="group relative">
                        Oh no! The shopping cart is completely empty!
                    </div>
                @endforelse




                <!-- More products... -->
            </div>
        </div>
    </div>
@endsection
