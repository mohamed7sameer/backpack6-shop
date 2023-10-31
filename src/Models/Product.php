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
        // 'properties' => 'array',

        // 'variations' => 'array',
        'extras' => 'array',
        'slug' => Slug::class,
    ];

    protected $appends = ['price_currency','sale_price_currency'];





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
    // public function getPropertiesAttribute($value): array
    public function getPropertiesAttribute($value): \Illuminate\Support\Collection
    {
        $_properties = (array)json_decode($value);
        // $_properties = collect(json_decode($value));
        // $properties = [];
        $properties = collect();
        // foreach ($_properties as $key => $_property) {
        foreach ($_properties as $_property) {
            $property = ProductProperty::find($_property->property_id);
            $property->property_id = $_property->property_id;
            $property->value = $_property->value;
            // $properties[$key] = $property;
            $properties->push($property) ;
        }

        // dd($properties);

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




    public function getVariationsAttribute($value):  \Illuminate\Support\Collection
    {
        $currencySign = config('mohamed7sameer.backpack-shop.currency.sign') ;
        $_variations = (array)json_decode($value);

        $variations = collect();
        foreach ($_variations as $_variation) {
            $variation = $_variation;

            $variation->price_currency =  $currencySign . ' ' .  number_format($_variation->price, 2, ',', '');
            $variation->sale_price_currency =  $currencySign . ' ' .  number_format($_variation->sale_price, 2, ',', '');
            // dd($variation);
            $variations->push($variation);
        }


        return $variations;

    }

    // public function getIsNewAttribute()
    // {
    //     $lastId = Product::latest()->take(10)->pluck('id')->toArray();
    //     if (in_array($this->id, $lastId)) {
    //         return true;
    //     }
    //     return false;
    // }


    public function getRecorderCategoriesAttribute()
    {
        $product_categories = $this->product_categories()->orderBy('lft')->get();

        return $product_categories;
    }

    public function getPriceCurrencyAttribute()
    {
        $currencySign = config('mohamed7sameer.backpack-shop.currency.sign') ;
        return $currencySign . ' ' .  number_format($this->price, 2, ',', '');
    }

    public function getSalePriceCurrencyAttribute()
    {
        $currencySign = config('mohamed7sameer.backpack-shop.currency.sign') ;
        return $currencySign . ' ' .  number_format($this->sale_price, 2, ',', '');
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
