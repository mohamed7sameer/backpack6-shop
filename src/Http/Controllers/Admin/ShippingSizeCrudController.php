<?php

namespace Eleven59\BackpackShop\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Eleven59\BackpackShop\Http\Requests\ShippingSizeRequest;
use Eleven59\BackpackShop\Models\ShippingSize;

/**
 * Class ShippingSizeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ShippingSizeCrudController extends CrudController
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
        CRUD::setModel(ShippingSize::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/shipping-size');
        CRUD::setEntityNameStrings(
            __('backpack-shop::shipping-size.crud.singular'),
            __('backpack-shop::shipping-size.crud.plural')
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
            ->label(__('backpack-shop::shipping-size.crud.name.label'));
        CRUD::orderBy('lft');

        Widget::add()
            ->type('alert')
            ->class('alert alert-danger mb-2')
            ->heading(__('backpack-shop::shipping-size.crud.list-notice.heading'))
            ->content(__('backpack-shop::shipping-size.crud.list-notice.content'))
            ->to('before_content');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation() :void
    {
        CRUD::setValidation(ShippingSizeRequest::class);

        CRUD::field('name')
            ->type('text')
            ->label(__('backpack-shop::shipping-size.crud.name.label'))
            ->hint(__('backpack-shop::shipping-size.crud.name.hint'));
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

        Widget::add()
            ->type('alert')
            ->class('alert alert-danger mb-2')
            ->heading(__('backpack-shop::shipping-size.crud.reorder-notice.heading'))
            ->content(__('backpack-shop::shipping-size.crud.reorder-notice.content'))
            ->to('before_content');
    }
}
