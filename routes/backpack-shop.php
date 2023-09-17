<?php

/*
|--------------------------------------------------------------------------
| Eleven59\BackpackShop Routes
|--------------------------------------------------------------------------
*/

/**
 * Payment result
 * Outside of the 'shopping-cart' prefix because this is the only URL that is shown publicly to the customer
 */
 Route::group([
     'middleware'=> array_merge(
     	(array) config('backpack.base.web_middleware', 'web'),
     ),
 ], function() {
     Route::get('payment-result', [\Eleven59\BackpackShop\Http\Controllers\OrderController::class, 'paymentResult']);
 });

/**
 * Shopping cart and checkout routes
 */
 Route::group([
     'prefix' => 'shopping-cart',
     'middleware'=> array_merge(
     	(array) config('backpack.base.web_middleware', 'web'),
     ),
 ], function() {
     Route::post('add-product', [\Eleven59\BackpackShop\Http\Controllers\CartController::class, 'add']);
     Route::post('update-product', [\Eleven59\BackpackShop\Http\Controllers\CartController::class, 'update']);
     Route::post('remove-product', [\Eleven59\BackpackShop\Http\Controllers\CartController::class, 'remove']);
     Route::post('checkout', [\Eleven59\BackpackShop\Http\Controllers\OrderController::class, 'processCheckout']);
     Route::post('payment', [\Eleven59\BackpackShop\Http\Controllers\OrderController::class, 'placeOrder']);
     Route::get('no-payment', [\Eleven59\BackpackShop\Http\Controllers\OrderController::class, 'noPayment']);
 });


/**
 * Admin Routes
 */
Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'Eleven59\BackpackShop\Http\Controllers\Admin',
], function () {
    Route::crud('vat-class', 'VatClassCrudController');
    Route::crud('shipping-region', 'ShippingRegionCrudController');
    Route::crud('shipping-size', 'ShippingSizeCrudController');
    Route::crud('shipping-rule', 'ShippingRuleCrudController');
    Route::crud('product-category', 'ProductCategoryCrudController');
    Route::crud('product-property', 'ProductPropertyCrudController');
    Route::crud('product-status', 'ProductStatusCrudController');
    Route::crud('product', 'ProductCrudController');
    Route::crud('order', 'OrderCrudController');
});

/**
 * Debug routes (only available in APP_DEBUG=true mode)
 */
Route::group([
    'prefix' => 'bpshop-test',
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web')
    ),
    'namespace' => 'Eleven59\BackpackShop\Http\Controllers\Admin',
], function() {
    if(env('APP_DEBUG')) {
        Route::get('/pdf/{order_id}', [\Eleven59\BackpackShop\Http\Controllers\DebugController::class, 'pdf']);
        Route::get('/pdf-html/{order_id}', [\Eleven59\BackpackShop\Http\Controllers\DebugController::class, 'pdf_html']);
        Route::get('/email/{order_id}', [\Eleven59\BackpackShop\Http\Controllers\DebugController::class, 'email']);
        Route::get('/email-html/{order_id}', [\Eleven59\BackpackShop\Http\Controllers\DebugController::class, 'email_html']);
    }
});
