<?php

namespace mohamed7sameer\BackpackShop\Casts;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Slug implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {

        // $is_slug = $model::where($key,$value)->first();

        if($model::where($key,$value)->exists() )
        {
            if($model::where($key,$value)->first()->id != $attributes['id']){
                $slug = SlugService::createSlug($model::class, 'slug', $attributes['name']);
                return $slug;
            }
        }
        return $value;

    }
}
