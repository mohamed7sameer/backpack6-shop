<?php

namespace Eleven59\BackpackShop\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Eleven59\BackpackShop\Http\Requests\ProductPropertyRequest;
use function config;

/**
 * Class ProductPropertyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductPropertyCrudController extends CrudController
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
        CRUD::setModel(\Eleven59\BackpackShop\Models\ProductProperty::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product-property');
        CRUD::setEntityNameStrings(
            __('backpack-shop::product-property.crud.singular'),
            __('backpack-shop::product-property.crud.plural')
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
        CRUD::column('title')
            ->type('text')
            ->label(__('backpack-shop::product-property.crud.title.label'));
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
        CRUD::setValidation(ProductPropertyRequest::class);

        CRUD::field('title')
            ->type('text')
            ->wrapper(['class' => (config('eleven59.backpack-shop.hide-slugs', true) ? 'form-group col-md-12' : 'form-group col-md-6')])
            ->label(__('backpack-shop::product-property.crud.title.label'));

        CRUD::field('slug')
            ->type('slug')
            ->target('title')
            ->wrapper(['class' => (config('eleven59.backpack-shop.hide-slugs', true) ? 'd-none' : 'form-group col-md-6')])
            ->label(__('backpack-shop::product-property.crud.slug.label'))
            ->hint(__('backpack-shop::product-property.crud.slug.hint'));
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
        CRUD::set('reorder.label', 'title');
        CRUD::set('reorder.max_level', 1);
    }
}
