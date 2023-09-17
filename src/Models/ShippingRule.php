<?php

namespace Eleven59\BackpackShop\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingRule extends Model
{
    use CrudTrait, HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'shipping_rules';
    protected $guarded = ['id', 'deleted_at', 'created_at', 'updated_at'];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate and return the total amount of VAT for this shipping rule
     * @return float
     */
    public function getVat() :float
    {
        $pricesIncludeVat = config('eleven59.backpack-shop.prices_include_vat', true);
        $vatMultiplier = 1 + ($this->shipping_vat_class->vat_percentage / 100);
        return $pricesIncludeVat ? ($this->price - ($this->price / $vatMultiplier)) : (($this->price * $vatMultiplier) - $this->price);
    }

    /**
     * Calculate and return the price for this shipping rule, excluding VAT
     * @return float
     */
    public function getPriceExclVat() :float
    {
        if(!config('eleven59.backpack-shop.prices_include_vat', true)) {
            return $this->price;
        }
        $vatMultiplier = 1 + ($this->shipping_vat_class->vat_percentage / 100);
        return ($this->price / $vatMultiplier);
    }


    /**
     * Calculate and return the price for this shipping rule, including VAT
     * @return float
     */
    public function getPriceInclVat() :float
    {
        if(config('eleven59.backpack-shop.prices_include_vat', true)) {
            return $this->price;
        }
        $vatMultiplier = 1 + ($this->shipping_vat_class->vat_percentage / 100);
        return ($this->price * $vatMultiplier);
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function shipping_region() :BelongsTo
    {
        return $this->belongsTo(ShippingRegion::class);
    }

    public function shipping_size() :BelongsTo
    {
        return $this->belongsTo(ShippingSize::class);
    }

    public function vat_class() :BelongsTo
    {
        return $this->belongsTo(VatClass::class);
    }

    public function shipping_vat_class() :BelongsTo
    {
        return $this->belongsTo(VatClass::class, 'shipping_vat_class_id');
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


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
