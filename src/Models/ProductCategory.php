<?php

namespace mohamed7sameer\BackpackShop\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use mohamed7sameer\BackpackShop\Traits\Image\HasImageFields;
use mohamed7sameer\BackpackShop\Traits\Image\HasImagesInRepeatableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use mohamed7sameer\BackpackShop\Casts\Slug;

class ProductCategory extends Model
{
    use CrudTrait, HasFactory, SoftDeletes, HasImageFields;
    use Sluggable;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'product_categories';
    protected $guarded = ['id', 'parent_id', 'lft', 'rgt', 'depth', 'deleted_at', 'created_at', 'updated_at'];
    protected $casts = [
        'slug' => Slug::class,

    ];

    protected $appends  = ['c_name'];

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


    public function getCNameAttribute()
    {

        $d = '';
        if($this->depth > 1)
        {
            for($i=$this->depth; $i > 1 ; $i--)
            {
                $d .= '__ ';
            }
        }
        return $d . $this->name;
    }

    public function getChildAttribute()
    {
        $categories = ProductCategory::where('parent_id','=', $this->id)->get();
        return $categories;
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

    // public function setMetaImageAttribute($value) :void
    // {
    //     $this->attributes['meta_image'] = $this->uploadImageData($value, [
    //         'delete_path' => $this->meta_image,
    //         'format' => 'jpg',
    //     ]);
    // }








    // public function setSlugAttribute($value){
    //     dd($value);
    // }

}
