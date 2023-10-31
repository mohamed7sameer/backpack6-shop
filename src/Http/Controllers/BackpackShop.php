<?php

namespace mohamed7sameer\BackpackShop\Http\Controllers;

use Illuminate\Routing\Controller;
use mohamed7sameer\BackpackShop\Models\Product;
use mohamed7sameer\BackpackShop\Models\ProductCategory;

class BackpackShop extends Controller
{

    public function categoriesIndex()
    {
        $menu = $this->getMenu();
        return view('mohamed7sameer.backpack-shop::backpack_shop.categories.index',compact('menu'));
    }

    public function categoriesShow($slug)
    {
        // $category = ProductCategory::where('slug',$slug)->first();
        $category = ProductCategory::whereSlug($slug)->first();

        return view('mohamed7sameer.backpack-shop::backpack_shop.categories.show',compact('category'));
    }


    public function productsIndex()
    {

        $products = Product::orderBy('price', 'asc')->get();
        return view('mohamed7sameer.backpack-shop::backpack_shop.products.index',compact('products'));
    }

    public function productsShow($slug)
    {
        $product = Product::whereSlug($slug)->first();
        return view('mohamed7sameer.backpack-shop::backpack_shop.products.show',compact('product'));
    }











    public function getMenu($id=null)
    {
        $categories = ProductCategory::where('parent_id',$id)->orderBy('lft')->get();

        $data = '';

        if($categories){

            $data .= '<ul>';
            foreach($categories as $category)
            {

                $data .= '<li>';

                $data .= '<a href="'
                    . route('bs-category-show',$category->slug)
                    . '">'
                    . $category->name
                    .'</a>';

                $data .= $this->getMenu($category->id);
                $data .= '</li>';
            }
            $data .= '</ul>';

        }

        return $data;
    }






}
