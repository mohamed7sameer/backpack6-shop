

@extends('mohamed7sameer.backpack-shop::backpack_shop.layout.main')




@section('content')
@php($currencySign = config('mohamed7sameer.backpack-shop.currency.sign'))
  <section class="overflow-hidden bg-white py-11 font-poppins dark:bg-gray-800">
      <div class="max-w-6xl px-4 py-4 mx-auto lg:py-8 md:px-6">
          <div class="flex flex-wrap -mx-4">
              <div class="w-full px-4 md:w-1/2 ">
                  <div class="sticky top-0 z-50 overflow-hidden ">
                      <div class="relative mb-6 lg:mb-10 lg:h-2/4 ">

                          <img id="main-image" src="{{Storage::disk('public')->url($product->cover)}}" alt=""
                              class="object-cover w-full lg:h-full ">
                      </div>
                      <div class="flex-wrap hidden md:flex ">

                        <div class="w-1/2 p-2 sm:w-1/4">
                            <a href="javascript:void(0)"
                                class="mo-photos block border border-blue-300 dark:border-transparent dark:hover:border-blue-300 hover:border-blue-300">
                                <img src="{{Storage::disk('public')->url($product->cover)}}" alt=""
                                    class="object-cover w-full lg:h-20">
                            </a>
                        </div>
                        @foreach ($product->photos as $photo)
                            <div class="w-1/2 p-2 sm:w-1/4">
                                <a href="javascript:void(0)"
                                    class="mo-photos block border border-blue-300 dark:border-transparent dark:hover:border-blue-300 hover:border-blue-300">
                                    <img src="{{$photo->photo}}" alt="{{$photo->description}}"
                                        class="object-cover w-full lg:h-20">
                                </a>
                            </div>
                        @endforeach

                      </div>
                  </div>
              </div>
              <div class="w-full px-4 md:w-1/2 ">
                  <div class="lg:pl-20">
                      <div class="mb-8 ">


                        {{-- @if ($product->isNew)
                        <span class="text-lg font-medium text-rose-500 dark:text-rose-200">New</span>
                        @endif --}}
                        <a href="{{route('bs-products-index')}}?product_status={{$product->product_status->id}}" class="text-lg font-medium text-rose-500 dark:text-rose-200">{{$product->product_status->status}}</a>





                          <h2 class="max-w-xl mt-2 mb-6 text-2xl font-bold dark:text-gray-400 md:text-4xl">
                              {{$product->name}}</h2>

                          <div class="max-w-md mb-8 text-gray-700 dark:text-gray-400">
                              {!!$product->description!!}
                          </div>
                          <p class="inline-block mb-8 text-4xl font-bold text-gray-700 dark:text-gray-400 ">
                              <span id="price">{{ $product->price_currency }}</span>
                              @if($product->sale_price)
                              <span id="sale_price"
                                  class="text-base font-normal text-gray-500 line-through dark:text-gray-400">
                                  {{ $product->sale_price_currency }}
                                </span>
                            @endif
                          </p>
                          {{-- <p class="text-green-600 dark:text-green-300 ">7 in stock</p> --}}
                      </div>

                      <div class="flex items-center mb-8">
                        <h2 class=" mr-6 text-xl font-bold dark:text-gray-400">
                            Categories:</h2>
                        <div class="">



                            @foreach ($product->recorder_categories as $category)

                                <li>
                                    <a
                                        href="{{route('bs-category-show',$category->slug)}}"
                                        class="text-lg font-medium text-rose-500 dark:text-rose-200">
                                        {{$category->name}}
                                    </a>
                                </li>

                            @endforeach

                        </div>
                    </div>



                      <div class="flex items-center mb-8">
                          <h2 class="w-16 mr-6 text-xl font-bold dark:text-gray-400">
                              Colors:</h2>
                          <div class="flex flex-wrap -mx-2 -mb-2" id="btns-color">
                            @foreach ($product->properties->where('slug','اللون') as $color)
                                <button onclick="setProperty({{$color->id}},this)"
                                    data-value="{{$color->value}}"
                                    @class([
                                        'p-1 mb-2 mr-2 border hover:border-blue-400 dark:border-gray-800 dark:hover:border-gray-400',
                                        // 'border-blue-400' => ($color->value == 'red'),
                                        ])
                                    >
                                    <div class="w-6 h-6 bg-{{$color->value}}-300"></div>
                            @endforeach
                        </button>


                          </div>
                      </div>
                      <div class="flex items-center mb-8">
                          <h2 class="w-16 text-xl font-bold dark:text-gray-400">
                              Size:</h2>
                          <div class="flex flex-wrap -mx-2 -mb-2">
                            @foreach ($product->properties->where('slug','الحجم') as $size)
                                <button onclick="setProperty({{$size->id}},this)"
                                    data-value="{{$size->value}}"
                                    @class([
                                        'py-1 mb-2 mr-1 border w-11 hover:border-blue-400 dark:border-gray-400 hover:text-blue-600 dark:hover:border-gray-300 dark:text-gray-400',
                                        // 'border-blue-400' => ($size->value == 'كبير'),
                                        ])
                                    >
                                    {{$size->value}}
                                </button>

                            @endforeach
                          </div>
                      </div>

                      <div class="flex items-center mb-8">
                        <h2 class="text-xl font-bold dark:text-gray-400">
                            الامكانيات:</h2>
                        <div class="flex flex-wrap mx-2 -mb-2">

                          @foreach ($product->variations as $variation)
                                {{-- @dd(json_encode($variation)) --}}
                              <button onclick="setVariation(this)"
                                  data-value="{{json_encode($variation)}}"
                                  @class([
                                      'py-1 mb-2 mr-1 border w-20 hover:border-blue-400 dark:border-gray-400 hover:text-blue-600
                                       dark:hover:border-gray-300 dark:text-gray-400',
                                      ])
                                  >
                                  {{$variation->description}}
                              </button>

                          @endforeach
                        </div>
                    </div>

                      <div class="w-32 mb-8 ">
                          <label for=""
                              class="w-full text-xl font-semibold text-gray-700 dark:text-gray-400">Quantity</label>
                          <div class="relative flex flex-row w-full h-10 mt-4 bg-transparent rounded-lg">
                              <button
                                    onclick="decrementQuantity()"
                                    class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 hover:text-gray-700 dark:bg-gray-900 hover:bg-gray-400">
                                    <span class="m-auto text-2xl font-thin">-</span>
                              </button>
                              <input type="number" min="1" step="5" name="quantity"
                                  class="flex items-center w-full font-semibold text-center text-gray-700 placeholder-gray-700 bg-gray-300 outline-none dark:text-gray-400 dark:placeholder-gray-400 dark:bg-gray-900 focus:outline-none text-md hover:text-black"
                                  placeholder="1">
                              <button
                                    onclick="incrementQuantity()"
                                    class="w-20 h-full text-gray-600 bg-gray-300 rounded-r outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 dark:bg-gray-900 hover:text-gray-700 hover:bg-gray-400">
                                    <span class="m-auto text-2xl font-thin">+</span>
                              </button>
                          </div>
                      </div>
                      <div class="flex flex-wrap items-center -mx-4 ">
                          <div class="w-full px-4 mb-4 lg:w-1/2 lg:mb-0">
                              <button
                                  class="flex items-center justify-center w-full p-4 text-blue-500 border border-blue-500 rounded-md dark:text-gray-200 dark:border-blue-600 hover:bg-blue-600 hover:border-blue-600 hover:text-gray-100 dark:bg-blue-600 dark:hover:bg-blue-700 dark:hover:border-blue-700 dark:hover:text-gray-300">
                                  Add to Cart
                              </button>
                          </div>
                          <div class="w-full px-4 mb-4 lg:mb-0 lg:w-1/2">
                              <button
                                  class="flex items-center justify-center w-full p-4 text-blue-500 border border-blue-500 rounded-md dark:text-gray-200 dark:border-blue-600 hover:bg-blue-600 hover:border-blue-600 hover:text-gray-100 dark:bg-blue-600 dark:hover:bg-blue-700 dark:hover:border-blue-700 dark:hover:text-gray-300">
                                  Add to wishlist
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>
@endsection


@push('scripts')

    <script>
        @include('mohamed7sameer.backpack-shop::backpack_shop.files.js.main',compact('product','currencySign'));
    </script>

@endpush

@push('styles')
    @include('mohamed7sameer.backpack-shop::backpack_shop.files.css.main')
@endpush



{{-- <br>
<input type="text" id='ajax-quantity' value="1"> --}}
{{-- @dd($product->variations) --}}

{{-- @foreach ($product->variations as $variation)
    <option value="{{ $variation->id }}">{{ $variation->description }}</option>
    <div>
        <img src="{{ Storage::disk('public')->url($category->cover) }}" alt="" width="100"
            height="100">
    </div>
@endforeach --}}

{{-- <a href="javascript:void(0)" onclick="add_product_ajax()" data-id="{{ $product->id }}">Add product Ajax</a>
<br>
<a href="">Add product shoppingcart</a> --}}
