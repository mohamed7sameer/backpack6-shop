<?php

namespace Eleven59\BackpackShop\Http\Controllers;

use Eleven59\BackpackShop\Http\Requests\CartRemoveRequest;
use Eleven59\BackpackShop\Http\Requests\CartUpdateRequest;
use Eleven59\BackpackShop\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class CartController extends Controller
{
    /**
     * Add item to cart
     * @param CartUpdateRequest $request
     * @return JsonResponse
     */
    public function add(CartUpdateRequest $request) :JsonResponse
    {
        list($product, $quantity, $variation) = $this->parseRequest($request);

        shoppingcart()->addItem($product, $quantity, $variation);

        // Sleep to test transitions
        if(env('APP_DEBUG')) {
            sleep(1);
        }

        return response()->json([
            'success' => true,
            'message' => __('backpack-shop::cart.controller.add.success'),
            'product_count' => shoppingcart()->product_count,
        ]);
    }

    /**
     * Update item quantity in cart
     * @param CartUpdateRequest $request
     * @return JsonResponse
     */
    public function update(CartUpdateRequest $request) :JsonResponse
    {
        list($product, $quantity, $variation) = $this->parseRequest($request);

        shoppingcart()->updateQuantity($product, $quantity, $variation);

        // Sleep to test transitions
        if(env('APP_DEBUG')) {
            sleep(1);
        }

        return response()->json([
            'success' => true,
            'message' => __('backpack-shop::cart.controller.update.success'),
            'product_count' => shoppingcart()->product_count,
        ]);
    }

    /**
     * Remove item from cart
     * @param CartRemoveRequest $request
     * @return JsonResponse
     */
    public function remove(CartRemoveRequest $request) :JsonResponse
    {
        list($product, $quantity, $variation) = $this->parseRequest($request);

        shoppingcart()->removeItem($product, $variation);

        // Sleep to test transitions
        if(env('APP_DEBUG')) {
            sleep(1);
        }

        return response()->json([
            'success' => true,
            'message' => __('backpack-shop::cart.controller.remove.success'),
            'product_count' => shoppingcart()->product_count,
        ]);
    }

    /**
     * Helper function to parse some default stuff so this does not need to be in all of the above
     * @param $request
     * @return array
     */
    protected function parseRequest($request)
    {
        $product = Product::findOrFail($request->get('product_id'));

        $quantity = $request->get('quantity', 1);
        if($quantity < 1) { $quantity = 1; }

        $variation = [];
        if(!empty($request->get('variation_id'))) {
            $variation = ['id' => $request->get('variation_id')];
        }

        return [
            $product,
            $quantity,
            $variation,
        ];
    }

}
