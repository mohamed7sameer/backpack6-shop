# Usage

This markdown file describes how to use this package to easily build a highly customizable shop frontend with shopping cart and checkout functionality using [Backpack for Laravel](https://backpackforlaravel.com).

## Contents:

- [1. Showing categories and products](./usage.md#1-showing-categories-and-products)
- [2. Using the shopping cart](./usage.md#2-using-the-shopping-cart)
- [3. Checkout and payment](./usage.md#3-checkout-and-payment)
- [4. Additional configuration](./usage.md#4-additional-configuration)

&nbsp;

---

## 1. Showing categories and products

Quick nav:

- [1.1 Categories](./usage.md#11-categories)
- [1.2 Products](./usage.md#12-products)
- [1.3 Product properties](./usage.md#13-product-properties)
- [1.4 Product variations](./usage.md#14-product-variations)

&nbsp;

### 1.1 Categories

Just use the `Eleven59\BackpackShop\Models\ProductCategory` model like you would any other CRUD model. For example:

```php
$categories = ProductCategory::has('products')->orderBy('name')->get();
```

and in your template:

```injectablephp
<ul class="category-menu">
    @foreach($categories as $category)
        <li><a href="/shop/{{ $category->slug }}/">{{ $category->name }}</a></li>
    @endforeach
<ul>
```

See `product_categories` table or CRUD for the available field names here.

&nbsp;

### 1.2 Products

Get all products in a category using `$category->products` or use the `Eleven59\BackpackShop\Models\Product` model like you would any other CRUD model. For example:

```php
// Category products
$category = ProductCategory::whereSlug('slug')->firstOrFail();
$products = $category->products;

// From model
$products = Product::orderBy('price', 'asc')->get();
```

and in your template:

```html
@forelse($category->products as $product)
    <div class="category-product" data-url="/shop/{{ $category->slug }}/{{ $product->slug }}">
        <div class="category-product-image">
            <img class="product-image" src="{{ $product->cover }}" alt="{{ $product->name }}">
        </div>
        <div class="category-product-info">
            <h3>{{ $product->name }}</h3>
        </div>
    </div>
@empty
    <div class="category-products-empty">
        No products in this category yet!
    </div>
@endforelse
```

See `products` table or CRUD for the available field names here, or add extra fields using the config file, or additional properties using the product properties CRUD (see 1.3 below).

&nbsp;

### 1.3 Product properties

This is pretty easy as well. Go to the `Product properties` CRUD panel and add a couple. Then edit or add a product on the `Products` CRUD panel, available at the end of the `Info` tab.

Display on the frontend using:

```html
@foreach($product->properties as $property)
    <span class="property-label">{{ $property->title}}: </span>
    <span class="property-value">{{ $property->value }}</span>
@endforeach
```

That's it!

&nbsp;

### 1.4 Product variations

Out of the box, this package provides the option to use product variations instead. These are versions of a product, which you can use for customers to order a specific type, but do not want to add as separate products.

Variations are accessible through the `$product->variations` property, and can have a different cover and price (but that's it). Think for example of selling a vinyl record in two colors, and making the color version a bit more expensive. That kind of stuff. See using the shopping cart for dealing with all that.

You may prefer just using single products, but this is here, too, if you need it.

&nbsp;

---

## 2. Using the shopping cart

Quick nav:

- [2.1 API: Add product](./usage.md#21-api-add-product)
- [2.2 API: Update product](./usage.md#22-api-update-product)
- [2.3 API: Remove product](./usage.md#23-api-remove-product)
- [2.4 Display contents](./usage.md#24-display-shopping-cart-contents)

&nbsp;

### 2.1 API: Add product

> Using the AJAX controller

This package automatically provides a route you can use to ajax-add a product to the shopping cart. Just call `/shopping-cart/add-product` with the following params:

```javascript
data = {
    "product_id": "", // required, id must exist in products table,
    "quantity": "", // required, must be integer,
    "variation_id": "" // required only if using product variations
}
```

The ajax call will return the following:

```javascript
result = {
    "success": true,
    "message": "", // success message, can be customized in the cart.php lang file,
    "product_count": 1, // number of products in the cart now,
}
```

The `product_count` variable is provided so you can update the cart product counter if you are using that somewhere in your templates.

> Using the shoppingcart() helper

If you need do all of the above yourself for whatever reason, you can use the `shoppingcart()->addItem(Product $product, int $quantity = 1, array $variation = [])` function.

- `$product` needs to be an instance of `Eleven59\BackpackShop\Models\Product`
- `$quantity` needs to be a positive integer
- `$variation` needs to be empty, or an array containing at least the `'id'` property of the variation you want to add

&nbsp;

### 2.2 API: Update product

> Using the AJAX controller

This package automatically provides a route you can use to ajax-update the shopping cart. Just call `/shopping-cart/update-product` with the following params:

```javascript
data = {
    "product_id": "", // required, id must exist in products table and cart
    "quantity": 1, // required, must be integer,
    "variation_id": "", // required if using product variations
}
```

The ajax call will return the following:

```javascript
result = {
    "success": true, // false if product id or variation was not in the cart
    "message": "", // success message, can be customized in the cart.php lang file,
    "product_count": 1, // number of products in the cart now,
}
```

The `product_count` variable is again provided so you can update the cart product counter if you are using that somewhere in your templates.

> Using the shoppingcart() helper

If you need do all of the above yourself for whatever reason, you can use the `shoppingcart()->updateQuantity(Product $product, int $quantity = 1, array $variation = [])` function.

- `$product` needs to be an instance of `Eleven59\BackpackShop\Models\Product`
- `$quantity` needs to be a positive integer
- `$variation` needs to be empty, or an array containing at least the `'id'` property of the variation you want to update

&nbsp;

### 2.3 API: Remove product

> Using the AJAX controller

This package automatically provides a route you can use to ajax-update the shopping cart. Just call `/shopping-cart/remove-product` with the following params:

```javascript
data = {
    "product_id": "", // required, id must exist in products table and cart
    "variation_id": "", // required if using product variations
}
```

The ajax call will return the following:

```javascript
result = {
    "success": true, // false if product id or variation was not in the cart
    "message": "", // success message, can be customized in the cart.php lang file,
    "product_count": 0, // number of products in the cart now,
}
```

The `product_count` variable is again provided so you can update the cart product counter if you are using that somewhere in your templates.

> Using the shoppingcart() helper

If you need do all of the above yourself for whatever reason, you can use the `shoppingcart()->removeItem(Product $product, array $variation = [])` function.

- `$product` needs to be an instance of `Eleven59\BackpackShop\Models\Product`
- `$variation` needs to be empty, or an array containing at least the `'id'` property of the variation you want to remove

&nbsp;

### 2.4 Display shopping cart contents

The `shoppingcart()` helper provides some tools to show the cart contents.

&nbsp;

#### Number of products

The `shoppingcart()->product_count` variable can be used to display how many products are currently in the shopping cart.

&nbsp;

#### Products

Using `shoppingcart()->products`, you can display the products in the cart:

```html
@php($currencySign = config('eleven59.backpack-shop.currency.sign'))
@forelse(shoppingcart()->products as $product)
    <div class="cart-product">
        <div class="cart-product-img">
            <img src="{{ $product->cover }}">
        </div>
        <div class="cart-product-info">
            <div class="product-qty-name">
                {{ $product->quantity }} x <strong>{{ $product->name }}</strong>
            </div>
            <div class="cart-product-price">
                {{ $currencySign }} {{ number_format($product->price, 2, ',', '') }}
            </div>
            <div class="cart-product-total">
                {{ $currencySign }} {{ number_format($product->price * $product->quantity, 2, ',', '') }}
            </div>
        </div>
    </div>
@empty
    <div class="no-products">Oh no! The shopping cart is completely empty! <a href="/shop">Feed it!</a></div>
@endforelse
```

&nbsp;

#### Totals

The cart also provides an easy way to display totals. You can get an array with all the totals you need using `shoppingcart()->totals`, which will give you the following response:

```php
[
    'subtotal_incl_vat' => (float),
    'subtotal_excl_vat' => (float),
    'vat_subtotal' => (float),
    'shipping_incl_vat' => (float),
    'shipping_excl_vat' => (float),
    'shipping_vat' => (float),
    'shipping_description' => (string) // available only if shipping country has already been set; see below,
    'total_incl_vat' => (float),
    'total_excl_vat' => (float),
    'vat_total' => (float),
    'vat' => [
        [
            'description' => (string) // name of the VAT class taken from CRUD
            'vat' => (float) // amount of vat for this VAT class
        ]
        ...        
    ],
]
```

Use these in your template as you see fit.

&nbsp;

---

## 3. Checkout and payment

Quick nav:

- [3.1 Checkout form: address and information](./usage.md#31-checkout-form-address-and-information)
- [3.2 Shipping and payment](./usage.md#32-shipping-and-payment)
- [3.3 Using custom payment providers](./usage.md#33-using-custom-payment-providers)
- [3.4 Processing payment and order confirmation](./usage.md#34-processing-payment-and-order-confirmation)

&nbsp;

### 3.1 Checkout form: address and information

This package is a backend/CRUD focused package, which means that it does not include templating for the frontend of the checkout process. It does, however, provide controller functionality to process the required information to accept an order and create a payment request.

By default, you can send the checkout form with address info to `/shopping-cart/checkout` using a `POST` request. This route saves the information to the cart and redirects the customer to the next step (for which you need to provide the URL, see below).

In addition to the `@csfr` field, the form needs to include at least:

| name         | type   | comment                                                                                         |
|--------------|--------|-------------------------------------------------------------------------------------------------|
| email        | email  | needs to be a valid email address                                                               |
| name         | string |                                                                                                 |
| address      | string |                                                                                                 |
| zipcode      | string |                                                                                                 |
| city         | string |                                                                                                 |
| country      | string | best to use a select that only shows countries for which valid shipping rules exist<sup>1</sup> |
| redirect_url | string | where to send the customer after validating the address info (see 3.2 below)                    |

#### <sup>1</sup> Show only countries with valid shipping rules:

This package comes with a function `bpshop_shipping_countries()` that you can use to populate a select field with only countries that have valid shipping rules. Full example:

```html
<select class="select2" name="country" id="checkout-country">
    @php($current_country = old('country', shoppingcart()->getAddress('country')) ?: config('eleven59.backpack-shop.default_shipping_country'))
    @foreach(bpshop_shipping_countries() as $country)
        <option value="{{ $country }}" {{ $current_country === $country ? 'selected' : '' }}>{{ $country }}</option>
    @endforeach
</select>
```

&nbsp;

### 3.2 Shipping and payment

The next screen should show the customer a summary of the order, the information entered, and the shipping method that has been selected for them.

#### Address details

Showing address details is easy. You can use the `shoppingcart()->getAddress('type')`, where `type` can be any variable included in the table above (except `redirect_url`).

#### Shipping details

Showing the shipping details is also easy. By default, this package selects the cheapest available shipping option for the products in the cart, and the selected country. It is possible that no valid shipping rules are available. In that case, you need to stop the user here and tell them wat to do (although preferably, the shipping rules entered using the CRUD panels should cover all likely scenarios).

Use the `shoppingcart()->totals` array to get the shipping with or without VAT, as well as the shipping description. You can also use `shoppingcart()->shipping_incl_vat`, `shoppingcart()->shipping_excl_vat`, and `shoppingcart()->getShippingDescription()` directly, but using the totals array is probably easier.

#### Payment method

Depending on the payment method used, this section may change slightly. Refer to the documentation for the paymentmethod you've picked to see if you need to do something else. Usually, however, most of this will still apply.

By default, you can send the form with the selected payment method to `/shopping-cart/payment` using a `POST` request. This route saves the information to the cart and redirects the customer to the checkout URL that the payment provider will provide.

In addition to the `@csfr` field, the form needs to include at least:

| name           | type   | comment                                                               |
|----------------|--------|-----------------------------------------------------------------------|
| payment_method | id     | valid values depend on which payment method you are using<sup>1</sup> |

#### <sup>1</sup> Showing valid payment methods:

In order to show the payment methods valid for the payment provider selected in the `config/backpack-shop.php` file, you can use the helper function `shoppingcart()->getPaymentMethods()`. Full example:

```html
<select class="select2 payment-method" name="payment_method" id="payment-method">
    @foreach(shoppingcart()->getPaymentMethods() as $method)
        <option value="{{ $method['id'] }}" {{ old('payment_method', 'ideal') === $method['id'] ? 'selected' : '' }}>{{ $method['description'] }}</option>
    @endforeach
</select>
```

That should probably do the trick for most payment methods. When this form is submitted to `/shopping-cart/payment` using a `POST` request, the customer will be redirected to the checkout URL provided by the payment provider. After the payment (success or failure), the customer will be redirected back to the site. You can customize which view is shown to them at this point, in the `config/backpack-shop.php` file. See also 3.4 below.

By default, if no additional payment method is installed, the package uses the "NoPayment" provider. This means that after this step, instead of redirecting the customer to a payment provider, the thanks page is immediately shown, and the order confirmations are automatically sent out as well.

&nbsp;

### 3.3 Using custom payment providers

See [payment-providers.md](./payment-providers.md)

&nbsp;

### 3.4 Processing payment and order confirmation

When the customer returns from the payment provider (or immediately, in the case of using the default NoPayment provider), they are shown the view which is configured in the `config/backpack-shop.php` file. 

**Note: the view should not require any variables present, otherwise this will generate an error on missing variables.**. 

The default route provides the view with a `$payment_result` variable, which contains:

- `$payment_result['status']` which has the order status ('new', 'paid', 'cancelled', or 'error')
- `$payment_result['msg']` for the translated message to go with that status (see `Order` class and `lang/*/order.php`)

At this point, the order is also available in the `Orders` CRUD panel. That's it, the order is now either fully processed or cancelled. By default, the shopping cart is emptied when an order is succesful (new or paid) but retained when the payment did not process correctly (cancelled or error) so the customer can easily try again without losing the cart.

The payment provider contains a function that determines when the order confirmation is sent out, at which point the customer will receive an email with their PDF invoice attached. The default `NoPayment` provider does this immediately when the order is placed, since there is no way to further process the order. Other payment providers may provide additional config here (again, see [payment-providers.md](./payment-providers.md)).

- E-mail contents can be customized by overriding the `backpack-shop/views/email/invoice` view. 
- PDF contents can be customized by overriding the `backpack-shop/views/pdf/invoice` view.
- The email and PDF can also be (partially) customized by publishing and editing the language files

&nbsp;

___

## 4 Additional configuration

There is a whole lot more that can be configured across this package. If I ever find the time (or please feel free to help me, see [contributing.md](../contributing.md)), I will try to put everything in a logical place. But this is quite a lot of work for stuff that is probably not useful for most scenarios. For now, I therefore suggest you look at the `config/backpack-shop.php` file, which does contain additional info and pointers that are not entirely covered in this usage doc.
