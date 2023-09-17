<?php

namespace Eleven59\BackpackShop\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Eleven59\BackpackShop\Http\Requests\ProductStatusRequest;
use function config;

/**
 * Class ProductStatusCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductStatusCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup() :void
    {
        CRUD::setModel(\Eleven59\BackpackShop\Models\ProductStatus::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product-status');
        CRUD::setEntityNameStrings(
            __('backpack-shop::product-status.crud.singular'),
            __('backpack-shop::product-status.crud.plural')
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
        CRUD::column('status')
            ->type('text')
            ->label(__('backpack-shop::product-status.crud.status.label'));
        CRUD::orderBy('status');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation() :void
    {
        CRUD::setValidation(ProductStatusRequest::class);

        CRUD::field('status')
            ->type('text')
            ->wrapper(['class' => (config('eleven59.backpack-shop.hide-slugs', true) ? 'form-group col-md-12' : 'form-group col-md-6')])
            ->label(__('backpack-shop::product-status.crud.status.label'));

        CRUD::field('slug')
            ->type('slug')
            ->target('status')
            ->wrapper(['class' => (config('eleven59.backpack-shop.hide-slugs', true) ? 'd-none' : 'form-group col-md-6')])
            ->label(__('backpack-shop::product-status.crud.slug.label'))
            ->hint(__('backpack-shop::product-status.crud.slug.hint'));

        CRUD::field('sales_allowed')
            ->type('switch')
            ->label(__('backpack-shop::product-status.crud.sales_allowed.label'))
            ->default(1);
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
}
