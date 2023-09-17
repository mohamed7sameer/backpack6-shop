<?php

namespace Eleven59\BackpackShop\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eleven59\BackpackImageTraits\Traits\HasImageFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use CrudTrait, HasFactory, SoftDeletes, HasImageFields;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'product_categories';
    protected $guarded = ['id', 'parent_id', 'lft', 'rgt', 'depth', 'deleted_at', 'created_at', 'updated_at'];


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

    public function products() :BelongsToMany
    {
        return $this->belongsToMany(Product::class);
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

    public function setCoverAttribute($value) :void
    {
        $this->attributes['cover'] = $this->uploadImageData($value, [
            'delete_path' => $this->cover,
            'format' => 'jpg',
        ]);
    }

    public function setMetaImageAttribute($value) :void
    {
        $this->attributes['meta_image'] = $this->uploadImageData($value, [
            'delete_path' => $this->meta_image,
            'format' => 'jpg',
        ]);
    }
}
