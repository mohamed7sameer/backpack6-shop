<?php

namespace Eleven59\BackpackShop\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Eleven59\BackpackShop\Http\Requests\ShippingRuleRequest;
use Eleven59\BackpackShop\Models\ShippingRegion;
use Eleven59\BackpackShop\Models\ShippingSize;
use Eleven59\BackpackShop\Models\VatClass;
use function config;

/**
 * Class ShippingRuleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ShippingRuleCrudController extends CrudController
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
        CRUD::setModel(\Eleven59\BackpackShop\Models\ShippingRule::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/shipping-rule');
        CRUD::setEntityNameStrings(
            __('backpack-shop::shipping-rule.crud.singular'),
            __('backpack-shop::shipping-rule.crud.plural')
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
        /* Required columns */
        CRUD::column('shipping_region_id')
            ->type('select')
            ->label(__('backpack-shop::shipping-rule.crud.shipping_region_id.label'))
            ->entity('shipping_region')
            ->attribute('name')
            ->model(ShippingRegion::class);
        if(bpshop_shipping_size_enabled()) {
            CRUD::column('shipping_size_id')
                ->type('select')
                ->label(__('backpack-shop::shipping-rule.crud.shipping_size_id.label'))
                ->entity('shipping_size')
                ->attribute('name')
                ->model(ShippingSize::class);
        }
        if(bpshop_shipping_weight_enabled()) {
            CRUD::column('max_weight')
                ->type('number')
                ->label(__('backpack-shop::shipping-rule.crud.max_weight.label'))
                ->suffix(' grams')
                ->decimals(0)
                ->thousands_sep('');
        }
        CRUD::column('price')
            ->type('number')
            ->label(__('backpack-shop::shipping-rule.crud.price.label'))
            ->prefix(config('backpack-shop.currency.sign', '€') . ' ')
            ->decimals(2)
            ->thousands_sep('');

        CRUD::orderBy('shipping_region_id')->orderBy('shipping_size_id')->orderBy('max_weight');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation() :void
    {
        CRUD::setValidation(ShippingRuleRequest::class);

        /* Required fields */
        CRUD::field('shipping_region_id')
            ->type('select2')
            ->label(__('backpack-shop::shipping-rule.crud.shipping_region_id.label'))
            ->entity('shipping_region')
            ->attribute('name')
            ->model(ShippingRegion::class);

        if(bpshop_shipping_size_enabled()) {
            CRUD::field('shipping_size_id')
                ->type('select2')
                ->label(__('backpack-shop::shipping-rule.crud.shipping_size_id.label'))
                ->entity('shipping_size')
                ->attribute('name')
                ->model(ShippingSize::class);
        }

        if(bpshop_shipping_weight_enabled()) {
            CRUD::field('max_weight')
                ->type('number')
                ->label(__('backpack-shop::shipping-rule.crud.max_weight.label'))
                ->suffix(__('backpack-shop::shipping-rule.crud.max_weight.suffix'));
        }

        CRUD::field('price')
            ->type('number')
            ->label(__('backpack-shop::shipping-rule.crud.price.label'))
            ->prefix(config('backpack-shop.currency.sign', '€'))
            ->attributes([
                'step' => '0.01',
            ])
            ->thousands_sep('');

        CRUD::field('shipping_vat_class_id')
            ->type('select2')
            ->label(__('backpack-shop::shipping-rule.crud.shipping_vat_class_id.label'))
            ->hint(__('backpack-shop::shipping-rule.crud.shipping_vat_class_id.hint'))
            ->entity('shipping_vat_class')
            ->attribute('name')
            ->model(VatClass::class);

        /* Optional fields */
        if(bpshop_show_column('shipping-rule', 'vat_class_id')) {
            CRUD::field('vat_class_id')
                ->type('select2')
                ->label(__('backpack-shop::shipping-rule.crud.vat_class_id.label'))
                ->hint(__('backpack-shop::shipping-rule.crud.vat_class_id.hint'))
                ->entity('vat_class')
                ->attribute('name')
                ->model(VatClass::class);
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
}
