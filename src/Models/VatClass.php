<?php

namespace mohamed7sameer\BackpackShop\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VatClass extends Model
{
    use CrudTrait, HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'vat_classes';
    protected $guarded = ['id', 'deleted_at', 'created_at', 'updated_at'];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function products() :HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function shipping_rule() :HasMany
    {
        return $this->hasMany(ShippingRule::class);
    }

    public function shipping_shipping_rule() :HasMany
    {
        return $this->hasMany(ShippingRule::class, 'shipping_vat_class_id');
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
