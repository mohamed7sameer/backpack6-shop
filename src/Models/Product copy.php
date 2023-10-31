<?php

namespace mohamed7sameer\BackpackShop\Models;


use Backpack\CRUD\app\Models\Traits\CrudTrait;

use mohamed7sameer\BackpackShop\Traits\Image\HasImageFields;
use mohamed7sameer\BackpackShop\Traits\Image\HasImagesInRepeatableFields;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use mohamed7sameer\BackpackShop\Casts\Slug;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use CrudTrait, HasFactory, SoftDeletes, HasImageFields, HasImagesInRepeatableFields;
    use Sluggable;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'products';
    protected $guarded = ['id', 'parent_id', 'lft', 'rgt', 'depth', 'deleted_at', 'created_at', 'updated_at'];
    protected $casts = [
        'photos' => 'array',
        'shipping_sizes' => 'array',
        'features' => 'array',
        'properties' => 'array',
        'variations' => 'array',
        'extras' => 'array',
        'slug' => Slug::class,
    ];





    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }




    /**
     * Get an array of shipping sizes that are available for the quantity provided
     * @param $quantity
     * @return bool|array
     */
    public function getShippingSizes($quantity = 1): bool|array
    {
        $shipping_sizes = $this->shipping_sizes;
        if (!count($shipping_sizes)) {
            return false;
        }

        uasort($shipping_sizes, function ($a, $b) {
            if ((int)$a['max_product_count'] == (int)$b['max_product_count']) return 0;
            return (int)$a['max_product_count'] < (int)$b['max_product_count'] ? -1 : 1;
        });

        $shippingSizes = [];
        $maxLft = 0;
        foreach ($shipping_sizes as $shipping_size) {
            if ((int)$shipping_size['max_product_count'] === 0) {
                if ($shippingSize = ShippingSize::find($shipping_size['shipping_size_id'])) {
                    $shippingSizes[] = $shippingSize;
                    $maxLft = max($shippingSize->lft, $maxLft);
                }
            }
            if ((int)$shipping_size['max_product_count'] >= (int)$quantity) {
                if ($shippingSize = ShippingSize::find($shipping_size['shipping_size_id'])) {
                    $shippingSizes[] = $shippingSize;
                    $maxLft = max($shippingSize->lft, $maxLft);
                }
            }
        }

        $largerShippingSizes = ShippingSize::where('lft', '>', $maxLft)->get();
        foreach ($largerShippingSizes as $shippingSize) {
            $shippingSizes[] = $shippingSize;
        }

        return $shippingSizes;
    }

    /**
     * Find the appropriate sales price for this product/variation
     * Protected internal function to prevent confusion on the frontend with prices being incl or excl VAT
     * @param string $variation_id
     * @return float
     */
    protected function getSalesPrice(string $variation_id = null): float
    {
        $price = $this->sale_price ?? $this->price;
        if (!empty($variation_id)) {
            foreach ($this->variations as $variation) {
                if ($variation['id'] === $variation_id) {
                    $price = $variation['sale_price'] ?? $variation['price'] ?? $price;
                    break;
                }
            }
        }
        return $price;
    }

    /**
     * Returns the VAT amount for this product/variation
     * @param string $variation_id
     * @return float
     */
    public function getSalesVat(string $variation_id = null): float
    {
        $pricesIncludeVat = config('mohamed7sameer.backpack-shop.prices_include_vat', true);
        $price = $this->getSalesPrice($variation_id);
        $vatMultiplier = 1 + ($this->vat_class->vat_percentage / 100);
        return $pricesIncludeVat ? ($price - ($price / $vatMultiplier)) : (($price * $vatMultiplier) - $price);
    }

    /**
     * Returns the price for this product/variation, excluding VAT
     * @param string $variation_id
     * @return float
     */
    public function getSalesPriceExclVat(string $variation_id = null): float
    {
        $price = $this->getSalesPrice($variation_id);
        if (!config('mohamed7sameer.backpack-shop.prices_include_vat', true)) {
            return $price;
        }
        $vatMultiplier = 1 + ($this->vat_class->vat_percentage / 100);
        return ($price / $vatMultiplier);
    }

    /**
     * Returns the price for this product/variation, including VAT
     * @param string $variation_id
     * @return float
     */
    public function getSalesPriceInclVat(string $variation_id = null): float
    {
        $price = $this->getSalesPrice($variation_id);
        if (config('mohamed7sameer.backpack-shop.prices_include_vat', true)) {
            return $price;
        }
        $vatMultiplier = 1 + ($this->vat_class->vat_percentage / 100);
        return ($price * $vatMultiplier);
    }

    /**
     * Returns the summary of all available variations (used for the shopping cart)
     * @param string|null $variation_id
     * @return array|null
     */
    public function getVariationSummary(string $variation_id = null): null|array
    {
        if (empty($variation_id)) {
            return null;
        }

        $variation = null;
        foreach ($this->variations as $variation) {
            if ($variation['id'] === $variation_id) {
                return [
                    'variation_id' => $variation_id,
                    'description' => $variation['description'],
                ];
            }
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function product_categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class);
    }

    public function product_status(): BelongsTo
    {
        return $this->belongsTo(ProductStatus::class);
    }

    public function product_property(): BelongsTo
    {
        return $this->belongsTo(ProductProperty::class);
    }

    public function vat_class(): BelongsTo
    {
        return $this->belongsTo(VatClass::class);
    }

    public function shipping_size(): BelongsTo
    {
        return $this->belongsTo(ShippingSize::class);
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getPropertiesAttribute($value): array
    {
        $_properties = (array)json_decode($value);
        $properties = [];
        foreach ($_properties as $key => $_property) {
            $property = ProductProperty::find($_property->property_id);
            $property->property_id = $_property->property_id;
            $property->value = $_property->value;
            $properties[$key] = $property;
        }

        return $properties;
    }

    public function getPhotosAttribute($value): array
    {
        $_photos = (array)json_decode($value);

        $photos = [];
        foreach ($_photos as $_photo) {

            $photos[] = (object)$_photo;
        }
        return $photos;
    }

    public function getVariationsAttribute($value): array
    {
        $_photos = (array)json_decode($value);

        $photos = [];
        foreach ($_photos as $_photo) {

            $photos[] = (object)$_photo;
        }

        return $photos;
    }

    public function getIsNewAttribute()
    {
        $lastId = Product::latest()->take(10)->pluck('id')->toArray();
        if (in_array($this->id, $lastId)) {
            return true;
        }
        return false;
    }

    // public function checky($m,$c_ids,$model,$value=null){

    //     // 0 => 7,
    //     // 1 => 9,
    //     // 2 => 10,
    //     // 3 => 12,

    //     \Illuminate\Support\Facades\Log::alert('++++++++++');
    //     \Illuminate\Support\Facades\Log::alert('parent->id=>' . $m->parent_id);
    //     \Illuminate\Support\Facades\Log::alert('id=>' . $m->id);
    //     \Illuminate\Support\Facades\Log::alert('name=>' . $m->name);



    //     // dd($model->where('id',9)->first()->toArray());

    //     if($value != null)
    //     {
    //         \Illuminate\Support\Facades\Log::alert('test=>' . 'a-child');
    //         if($m->parent_id == null){
    //         \Illuminate\Support\Facades\Log::alert('test=>' . 'b-child-false');
    //             return false;
    //         }

    //         if($m->parent_id == $value){
    //         \Illuminate\Support\Facades\Log::alert('test=>' . 'c-child-true');
    //             return true;
    //         }

    //     }else{
    //         if($m->parent_id == null){
    //         \Illuminate\Support\Facades\Log::alert('test=>' . 'd-parent-true');
    //             return true;
    //         }

    //         // if($m->parent_id == $value){
    //         //     return false;
    //         // }
    //     }



    //     if(in_array($m->parent_id,$c_ids)){
    //     \Illuminate\Support\Facades\Log::alert('test=>' . 'e');
    //         if($value != null){
    //             \Illuminate\Support\Facades\Log::alert('test=>' . 'f-child-loop');
    //             $parent_parent_id = $model->where('id',$m->parent_id)->first();
    //             $this->checky($parent_parent_id,$c_ids,$model,$value);
    //         }

    //         else{
    //             \Illuminate\Support\Facades\Log::alert('test=>' . 'g-parent-false');
    //             return false ;
    //         }

    //     }else{
    //         \Illuminate\Support\Facades\Log::alert('test=>' . 'h');
    //         if($value != null){
    //             \Illuminate\Support\Facades\Log::alert('test=>' . 'i-child-false-loop');
    //             $parent_parent_id = $model->where('id',$m->parent_id)->first();
    //             $this->checky($parent_parent_id,$c_ids,$model,$value);
    //             // return false ;
    //         }else{
    //             \Illuminate\Support\Facades\Log::alert('test=>' . 'j-parent-loop');
    //             $parent_parent_id = $model->where('id',$m->parent_id)->first();
    //             $this->checky($parent_parent_id,$c_ids,$model,$value);
    //         }

    //     }
    //     \Illuminate\Support\Facades\Log::alert('----------');
    // }



    public function checky($m, $c_ids, $model, $value = null)
    {

        // 0 => 7,
        // 1 => 9,
        // 2 => 10,
        // 3 => 12,

        \Illuminate\Support\Facades\Log::alert('++++++++++');
        \Illuminate\Support\Facades\Log::alert('parent->id=>' . $m->parent_id);
        \Illuminate\Support\Facades\Log::alert('id=>' . $m->id);
        \Illuminate\Support\Facades\Log::alert('name=>' . $m->name);

        if ($m->parent_id == null) {
            \Illuminate\Support\Facades\Log::alert('test=>' . 'a');
            return true;
        }
        if (in_array($m->parent_id, $c_ids)) {
            \Illuminate\Support\Facades\Log::alert('test=>' . 'b');
            return false;
        } else {
            \Illuminate\Support\Facades\Log::alert('test=>' . 'c');
            $parent_parent_id = $model->where('id', $m->parent_id)->first();
            return $this->checky($parent_parent_id, $c_ids, $model, $value);
        }
        \Illuminate\Support\Facades\Log::alert('----------');
    }


    public function checky2($m, $c_ids, $model, $value = null)
    {

        // 0 => 7, - الفئة الاولي
        // 1 => 9, - مرحبا مرة اخري
        // 2 => 10, - cc
        // 3 => 12, - الفئة الثالثه




        \Illuminate\Support\Facades\Log::alert('000000000000000000000');
        \Illuminate\Support\Facades\Log::alert('parent->id=>' . $m->parent_id);
        \Illuminate\Support\Facades\Log::alert('id=>' . $m->id);
        \Illuminate\Support\Facades\Log::alert('name=>' . $m->name);







        if ($m->parent_id == null) {
            \Illuminate\Support\Facades\Log::alert('test=>' . 'a');
            return false;
        }

        if ($m->parent_id == $value) {
            \Illuminate\Support\Facades\Log::alert('test=>' . 'b');
            return true;
        }


        if (in_array($m->parent_id, $c_ids)) {
            \Illuminate\Support\Facades\Log::alert('test=>' . 'c');
            return true;
        } else {
            \Illuminate\Support\Facades\Log::alert('test=>' . 'd');
            $parent_parent_id = $model->where('id', $m->parent_id)->first();
            return $this->checky($parent_parent_id, $c_ids, $model, $value);
        }
        \Illuminate\Support\Facades\Log::alert('111111111111111111111111');
    }




    public function getMainCategoriesAttribute()
    {
        // $all_categories = ProductCategory::all();

        // $product_categories = $this->product_categories();

        // $c_ids = $product_categories->pluck('id')->toArray();

        // $categories_valids =  $product_categories->orderBy('lft')->get()

        //     ->filter(function ($value, $key) use ($c_ids, $all_categories) {

        //         return $this->checky($value, $c_ids, $all_categories);
        //     });


        // // dd($categories_valids->pluck('id')->toArray());

        // $data = "<ul>";

        // foreach ($categories_valids as $category_valid) {
        //     $data .= $this->getCategoriesListHtml($category_valid, $product_categories, $c_ids, $all_categories);
        // }

        // $data .= "</ul>";

        // return $data;


        $product_categories = $this->product_categories()->orderBy('lft')->get();

        $data1 = collect([]);
        $data2 = collect([]);

        // $data1 = [];
        // $data2 = [];



        $product_categories->each(function ($item, $key) use ($data1, $data2) {

            $data2->push(collect(['id' => $item->id, 'child' => collect([])]));
            if ($item->parent_id == null) {
                $data1->push($item->id);
            } else {
                \Illuminate\Support\Facades\Log::alert('---------000000---------');
                for ($i = 0; true; $i++) {

                    // \Illuminate\Support\Facades\Log::alert('------------------');
                    // \Illuminate\Support\Facades\Log::alert('i=>' . $i);
                    // \Illuminate\Support\Facades\Log::alert('data1=>' . $data1);
                    // \Illuminate\Support\Facades\Log::alert('data2=>' . $data2);
                    // \Illuminate\Support\Facades\Log::alert('item=>' . $item);



                    if ($data2[$i]['id'] == $item->parent_id) {
                        $data2->where('id', $item->parent_id)->first()['child']->push($item->id);
                        // \Illuminate\Support\Facades\Log::alert('true=> yes');
                        // \Illuminate\Support\Facades\Log::alert('$data2[$i][\'id\']=>' . $data2[$i]['id'] );
                        // \Illuminate\Support\Facades\Log::alert('$item->parent_id=>' . $item->parent_id );
                        break;
                        // $data2->put('id','value');
                    }else{
                        // \Illuminate\Support\Facades\Log::alert('true=> no');
                        // \Illuminate\Support\Facades\Log::alert('$data2[$i][\'id\']=>' . $data2[$i]['id'] );
                        // \Illuminate\Support\Facades\Log::alert('$item->parent_id=>' . $item->parent_id );


                        $pparent_id = $item->parent_id;
                        for($ii=1; true; $ii++)
                        {
                            $parentItem = ProductCategory::where('id', $pparent_id)->first();
                            if($data2[$i]['id'] == $parentItem->parent_id){
                                \Illuminate\Support\Facades\Log::alert($parentItem->parent_id);
                                \Illuminate\Support\Facades\Log::alert($pparent_id);
                                \Illuminate\Support\Facades\Log::alert($item->parent_id);
                                \Illuminate\Support\Facades\Log::alert($item->id);
                                \Illuminate\Support\Facades\Log::alert($data2);
                                $data2->where('id', $parentItem->parent_id)->first()['child']->push($item->id);
                                break ;
                            }
                            if($parentItem->depth > 1){
                                $pparent_id = $parentItem->parent_id;
                            }else{
                                break;
                            }
                        }


                    }
                    \Illuminate\Support\Facades\Log::alert('+++++++++++++++++');

                    if($i >= count($data2)-1)
                    {
                        break;
                    }
                }

                \Illuminate\Support\Facades\Log::alert('+++++++0000000000++++++++++');

                // if($init == false){
                //     \Illuminate\Support\Facades\Log::alert('-------00-----------');
                //     \Illuminate\Support\Facades\Log::alert('item->id=>' . $item->id);
                //     \Illuminate\Support\Facades\Log::alert('+++++++00++++++++++');
                //     // dd($item->id);
                // }
                // \Illuminate\Support\Facades\Log::alert('0000000000000000000');

                // if($this->oldItemId == $item->parent_id)
                // {

                //     $data1->where('id',$item->parent_id)->first()['child']->push($item->id) ;
                //     // $data2->put('id','value');
                // }
                // // $data2->push($item);
            }

            // $this->oldItemId = $item->id;

            // \Illuminate\Support\Facades\Log::alert('------------------');
            // \Illuminate\Support\Facades\Log::alert('id=>' . $item->id);
            // \Illuminate\Support\Facades\Log::alert('parent_id=>' . $item->parent_id);
            // \Illuminate\Support\Facades\Log::alert('name=>' . $item->name);
            // \Illuminate\Support\Facades\Log::alert('+++++++++++++++++');

        });
        dd($data2->toArray());
        // dd($data1->toArray());
        //    dd($this->oldItemId);
        //    dd($data1->toArray());
        //    dd($data1->first()->toArray());


    }


    public function getCategoriesListHtml($category, $product_categories, $c_ids, $all_categories)
    {

        // $data =  "<li><span>" . $category->c_name . "-" .  $category->id  . "</span>";

        // $child = $product_categories->orderBy('lft')->get()



        //     ->filter(function ($value, $key) use ($c_ids, $all_categories, $category) {

        //         return $this->checky2($value, $c_ids, $all_categories, $category->id);
        //     });

        // dd($child->toArray());




        // // // dd($c_ids);
        // // dd($c_ids);
        // // dd($category->toArray());
        // dd($child->toArray());
        // if (count($child) != 0) {


        //     // \Illuminate\Support\Facades\Log::alert('++++++++++');
        //     // \Illuminate\Support\Facades\Log::alert($child);
        //     // \Illuminate\Support\Facades\Log::alert('----------');

        //     $data .= "<ul>";

        //     foreach ($child as $ch) {
        //         $data .= $this->getCategoriesListHtml($ch, $product_categories, $c_ids, $all_categories);
        //     }

        //     $data .= "</ul>";
        // }


        // $data .= "</li>";

        // return $data;
    }




    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    // public function setCoverAttribute($value) :void
    // {
    //     $this->attributes['cover'] = $this->uploadImageData($value, [
    //         'delete_path' => $this->cover,
    //         'format' => 'jpg',
    //     ]);
    // }

    public function setPhotosAttribute($value): void
    {
        // dd($value);
        if (!empty($value)) {
            $this->attributes['photos'] = $this->uploadRepeatableImageData($value, [
                'format' => 'jpg',
                'directory' => 'product/photos',

            ]);
        }
    }

    public function setVariationsAttribute($value): void
    {
        if (!empty($value)) {
            $this->attributes['variations'] = $this->uploadRepeatableImageData($value, [
                'format' => 'jpg',
                'directory' => 'product/variations',

            ]);
        }
    }

    // public function setMetaImageAttribute($value) :void
    // {
    //     $this->attributes['meta_image'] = $this->uploadImageData($value, [
    //         'delete_path' => $this->meta_image,
    //         'format' => 'jpg',
    //     ]);
    // }
}
