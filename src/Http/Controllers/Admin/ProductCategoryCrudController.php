<?php

namespace Eleven59\BackpackShop\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Eleven59\BackpackShop\Http\Requests\ProductCategoryRequest;
use function config;

/**
 * Class ProductCategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup() :void
    {
        CRUD::setModel(\Eleven59\BackpackShop\Models\ProductCategory::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product-category');
        CRUD::setEntityNameStrings(
            __('backpack-shop::product-category.crud.singular'),
            __('backpack-shop::product-category.crud.plural')
        );
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation() :void
    {
        CRUD::column('name')
            ->type('text')
            ->label(__('backpack-shop::product-category.crud.name.label'));
        CRUD::orderBy('lft');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation() :void
    {
        CRUD::setValidation(ProductCategoryRequest::class);

        CRUD::field('name')
            ->tab(__('backpack-shop::product-category.crud.tabs.info'))
            ->type('text')
            ->wrapper(['class' => (config('eleven59.backpack-shop.hide-slugs', true) ? 'form-group col-md-12' : 'form-group col-md-6')])
            ->label(__('backpack-shop::product-category.crud.name.label'));

        CRUD::field('slug')
            ->tab(__('backpack-shop::product-category.crud.tabs.info'))
            ->type('slug')
            ->target('name')
            ->wrapper(['class' => (config('eleven59.backpack-shop.hide-slugs', true) ? 'd-none' : 'form-group col-md-6')])
            ->label(__('backpack-shop::product-category.crud.slug.label'))
            ->hint(__('backpack-shop::product-category.crud.slug.hint'));

        if(bpshop_show_column('product-category', 'description')) {
            CRUD::field('description')
                ->tab(__('backpack-shop::product-category.crud.tabs.info'))
                ->type('wysiwyg')
                ->label(__('backpack-shop::product-category.crud.description.label'));
        }

        if(bpshop_show_column('product-category', 'cover')) {
            $coverField = CRUD::field('cover')
                ->tab(__('backpack-shop::product-category.crud.tabs.media'))
                ->type('image')
                ->label(__('backpack-shop::product-category.crud.cover.label'))
                ->aspect_ratio(config('eleven59.backpack-shop.category-cover.aspect-ratio', 0))
                ->crop(config('eleven59.backpack-shop.category-cover.crop', true))
                ->disk(config('eleven59.backpack-shop.category-cover.disk', null))
                ->prefix(config('eleven59.backpack-shop.category-cover.prefix', null));
        }

        /* Meta/SEO fields */
        if(bpshop_show_column('product-category', 'meta-title')) {
            CRUD::field('meta_title')
                ->tab(__('backpack-shop::product-category.crud.tabs.seo'))
                ->type('text')
                ->label(__('backpack-shop::product-category.crud.meta-title.label'))
                ->hint(__('backpack-shop::product-category.crud.meta-title.hint'));
        }

        if(bpshop_show_column('product-category', 'meta-description')) {
            CRUD::field('meta_description')
                ->tab(__('backpack-shop::product-category.crud.tabs.seo'))
                ->type('textarea')
                ->attributes([
                    'rows' => '3',
                    'maxlength' => '160',
                    'style' => 'resize: none',
                ])
                ->label(__('backpack-shop::product-category.crud.meta-description.label'))
                ->hint(__('backpack-shop::product-category.crud.meta-description.hint'));
        }

        if(bpshop_show_column('product-category', 'meta-image')) {
            CRUD::field('meta_image')
                ->tab(__('backpack-shop::product-category.crud.tabs.seo'))
                ->type('image')
                ->label(__('backpack-shop::product-category.crud.meta-image.label'))
                ->hint(__('backpack-shop::product-category.crud.meta-image.hint'))
                ->aspect_ratio(config('eleven59.backpack-shop.category-meta-image.aspect-ratio', 1.91))
                ->crop(config('eleven59.backpack-shop.category-meta-image.crop', true))
                ->disk(config('eleven59.backpack-shop.category-meta-image.disk', null))
                ->prefix(config('eleven59.backpack-shop.category-meta-image.prefix', null));
        }
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation() :void
    {
        $this->setupCreateOperation();
    }

    public function setupReorderOperation() :void
    {
        CRUD::set('reorder.label', 'name');
        CRUD::set('reorder.max_level', 1);
    }
}
