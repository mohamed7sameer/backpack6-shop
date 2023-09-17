<?php

namespace Eleven59\BackpackShop\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ShippingRegion extends Model
{
    use CrudTrait, HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'shipping_regions';
    protected $guarded = ['id', 'deleted_at', 'created_at', 'updated_at'];
    protected $casts = ['countries' => 'array'];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Look up the right shipping region for the country provided (using name.common from PragmaRX/Countries)
     * If multiple regions match, the first one is used; it's up to the user to not double-assign countries
     * @param string $countryCommonName
     * @return bool|ShippingRegion
     */
    public static function getByCountry(string $countryCommonName) :bool|ShippingRegion
    {
        if(!$shipping_region = DB::table('shipping_regions')->select('id')->where('countries', 'like', "%\"{$countryCommonName}\"%")->orderBy('id')->first()) {
            return false;
        }

        return ShippingRegion::find($shipping_region->id);
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function shipping_rule() :HasMany
    {
        return $this->hasMany(ShippingRule::class);
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
