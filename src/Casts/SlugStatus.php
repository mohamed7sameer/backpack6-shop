<?php

namespace mohamed7sameer\BackpackShop\Casts;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class SlugStatus implements CastsAttributes
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
        // dd('Hi') ;
        $is_slug = $model::where($key,$value)->first();
        if($is_slug)
        {
            $slug = SlugService::createSlug($model::class, 'slug', $attributes['status']);
            return $slug;
        }else{
            return $value;
        }

    }
}
