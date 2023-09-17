<?php

namespace Eleven59\BackpackShop\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Eleven59\BackpackShop\Http\Requests\ShippingRegionRequest;
use function config;

/**
 * Class ShippingRegionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ShippingRegionCrudController extends CrudController
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
        CRUD::setModel(\Eleven59\BackpackShop\Models\ShippingRegion::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/shipping-region');
        CRUD::setEntityNameStrings(
            __('backpack-shop::shipping-region.crud.singular'),
            __('backpack-shop::shipping-region.crud.plural')
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
            ->label(__('backpack-shop::shipping-region.crud.name.label'));
        CRUD::orderBy('name');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation() :void
    {
        CRUD::setValidation(ShippingRegionRequest::class);

        CRUD::field('name')
            ->type('text')
            ->label(__('backpack-shop::shipping-region.crud.name.label'))
            ->hint(__('backpack-shop::shipping-region.crud.name.hint'));

        CRUD::field('countries')
            ->type('countries')
            ->label(__('backpack-shop::shipping-region.crud.countries.label'));
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
