<?php

namespace Eleven59\BackpackShop\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use function config;

/**
 * Class OrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OrderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Eleven59\BackpackShop\Http\Controllers\Admin\Operations\DetailsOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\Eleven59\BackpackShop\Models\Order::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/order');
        CRUD::setEntityNameStrings(
            __('backpack-shop::order.crud.singular'),
            __('backpack-shop::order.crud.plural')
        );
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('created_at')
            ->type('datetime')
            ->label(__('backpack-shop::order.crud.created_at.label'))
            ->format(config('backpack.base.default_datetime_format', 'D MMM YYYY, HH:mm'));

        CRUD::column('fancy_status')
            ->type('text')
            ->label(__('backpack-shop::order.crud.status.label'));

        CRUD::column('fancy_invoice_no')
            ->type('text')
            ->label(__('backpack-shop::order.crud.order_no.label'));

        CRUD::column('full_address')
            ->type('textarea')
            ->escaped(false)
            ->label(__('backpack-shop::order.crud.full_address.label'));

        CRUD::column('order_items')
            ->type('textarea')
            ->escaped(false)
            ->label(__('backpack-shop::order.crud.order_items.label'));

        CRUD::column('order_total')
            ->type('number')
            ->decimals(2)
            ->prefix(config('eleven59.backpack-shop.currency.sign', '€') . ' ')
            ->label(__('backpack-shop::order.crud.order_total.label'));

        CRUD::orderBy('created_at', 'desc');

        CRUD::addFilter([
            'name' => 'status',
            'type' => 'dropdown',
            'label'=> 'Status'
        ], [
            'new' => __('backpack-shop::order.statuses.new'),
            'cancelled' => __('backpack-shop::order.statuses.cancelled'),
            'paid' => __('backpack-shop::order.statuses.paid'),
        ], function($value) {
            CRUD::addClause('where', 'status', $value);
        });

        CRUD::enableExportButtons();
    }
}
