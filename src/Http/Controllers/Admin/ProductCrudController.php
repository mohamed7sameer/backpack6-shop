<?php

namespace Eleven59\BackpackShop\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Eleven59\BackpackShop\Http\Requests\ProductRequest;
use Eleven59\BackpackShop\Models\ProductProperty;
use Eleven59\BackpackShop\Models\ProductCategory;
use Eleven59\BackpackShop\Models\ProductStatus;
use Eleven59\BackpackShop\Models\ShippingSize;
use Eleven59\BackpackShop\Models\VatClass;
use function config;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
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
        CRUD::setModel(\Eleven59\BackpackShop\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings(
            __('backpack-shop::product.crud.singular'),
            __('backpack-shop::product.crud.plural')
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
            ->label(__('backpack-shop::product.crud.name.label'));
        CRUD::column('product_categories')
            ->type('select_multiple')
            ->label(__('backpack-shop::product.crud.product_categories.label'))
            ->entity('product_categories')
            ->attribute('name')
            ->model(ProductCategory::class)
            ->pivot(true);
        CRUD::column('price')
            ->type('number')
            ->label(__('backpack-shop::product.crud.price.label'))
            ->prefix(config('backpack-shop.currency.sign', '€') . ' ')
            ->decimals(2)
            ->thousands_sep('');
        if(bpshop_show_column('product', 'sale_price')) {
            CRUD::column('sale_price')
                ->type('number')
                ->label(__('backpack-shop::product.crud.sale_price.label'))
                ->prefix(config('backpack-shop.currency.sign', '€') . ' ')
                ->decimals(2)
                ->thousands_sep('');
        }
        CRUD::column('product_status_id')
            ->type('select')
            ->label(__('backpack-shop::product.crud.product_status_id.label'))
            ->entity('product_status')
            ->attribute('status')
            ->model(ProductStatus::class);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation() :void
    {
        CRUD::setValidation(ProductRequest::class);

        CRUD::field('name')
            ->tab(__('backpack-shop::product.crud.tabs.info'))
            ->type('text')
            ->wrapper(['class' => (config('eleven59.backpack-shop.hide-slugs', true) ? 'form-group col-md-12' : 'form-group col-md-6')])
            ->label(__('backpack-shop::product.crud.name.label'));

        CRUD::field('slug')
            ->tab(__('backpack-shop::product.crud.tabs.info'))
            ->type('slug')
            ->target('name')
            ->wrapper(['class' => (config('eleven59.backpack-shop.hide-slugs', true) ? 'd-none' : 'form-group col-md-6')])
            ->label(__('backpack-shop::product.crud.slug.label'))
            ->hint(__('backpack-shop::product.crud.slug.hint'));

        if(bpshop_show_column('product', 'sku')) {
            CRUD::field('description')
                ->tab(__('backpack-shop::product.crud.tabs.info'))
                ->type('text')
                ->label(__('backpack-shop::product.crud.sku.label'));
        }

        CRUD::field('product_categories')
            ->tab(__('backpack-shop::product.crud.tabs.info'))
            ->type('select2_multiple')
            ->wrapper(['class' => 'form-group col-md-6'])
            ->label(__('backpack-shop::product.crud.product_categories.label'))
            ->entity('product_categories')
            ->attribute('name')
            ->model(ProductCategory::class)
            ->pivot(true);

        CRUD::field('product_status_id')
            ->tab(__('backpack-shop::product.crud.tabs.info'))
            ->type('select2')
            ->wrapper(['class' => 'form-group col-md-6'])
            ->label(__('backpack-shop::product.crud.product_status_id.label'))
            ->entity('product_status')
            ->attribute('status')
            ->model(ProductStatus::class);

        if(bpshop_show_column('product', 'description')) {
            CRUD::field('description')
                ->tab(__('backpack-shop::product.crud.tabs.info'))
                ->type('wysiwyg')
                ->label(__('backpack-shop::product.crud.description.label'));
        }

        if(bpshop_show_column('product', 'properties')) {
            CRUD::field('properties')
                ->tab(__('backpack-shop::product.crud.tabs.info'))
                ->type('repeatable')
                ->init_rows(0)
                ->label(__('backpack-shop::product.crud.properties.label'))
                ->subfields([
                    [
                        'name' => 'property_id',
                        'type' => 'select2_from_array',
                        'wrapper' => ['class' => 'form-group col-md-6'],
                        'label' => __('backpack-shop::product.crud.properties.property_id.label'),
                        'options' => ProductProperty::orderBy('title')->pluck('title', 'id'),
                    ],
                    [
                        'name' => 'value',
                        'type' => 'text',
                        'wrapper' => ['class' => 'form-group col-md-6'],
                        'label' => __('backpack-shop::product.crud.properties.value.label'),
                    ],
                ])
                ->reorder(true)
                ->new_item_label(__('backpack-shop::product.crud.properties.new_item_label'));
        }

        if(bpshop_show_column('product', 'cover')) {
            CRUD::field('cover')
                ->tab(__('backpack-shop::product.crud.tabs.media'))
                ->type('image')
                ->label(__('backpack-shop::product.crud.cover.label'))
                ->aspect_ratio(config('eleven59.backpack-shop.product-cover.aspect-ratio', 0))
                ->crop(config('eleven59.backpack-shop.product-cover.crop', true))
                ->disk(config('eleven59.backpack-shop.product-cover.disk', null))
                ->prefix(config('eleven59.backpack-shop.product-cover.prefix', null));
        }

        if(bpshop_show_column('product', 'photos')) {
            CRUD::field('photos')
                ->tab(__('backpack-shop::product.crud.tabs.media'))
                ->type('repeatable')
                ->init_rows(0)
                ->label(__('backpack-shop::product.crud.photos.label'))
                ->subfields([
                    [
                        'name' => 'photo',
                        'type' => 'image',
                        'label' => __('backpack-shop::product.crud.photos.photo.label'),
                        'aspect_ratio' => config('eleven59.backpack-shop.product-photos.aspect-ratio', 0),
                        'crop' => config('eleven59.backpack-shop.product-photos.crop', true),
                        'disk' => config('eleven59.backpack-shop.product-photos.disk', null),
                        'prefix' => config('eleven59.backpack-shop.product-photos.prefix', null),
                    ],
                    [
                        'hint' => __('backpack-shop::product.crud.photos.description.hint'),
                        'name' => 'description',
                        'type' => 'text',
                        'label' => __('backpack-shop::product.crud.photos.description.label'),
                    ],
                ])
                ->reorder(true)
                ->new_item_label(__('backpack-shop::product.crud.photos.new_item_label'));
        }

        CRUD::field('price')
            ->tab(__('backpack-shop::product.crud.tabs.sales'))
            ->type('number')
            ->label(__('backpack-shop::product.crud.price.label'))
            ->prefix(config('backpack-shop.currency.sign', '€'))
            ->wrapper(['class' => (bpshop_show_column('product', 'sale_price') ? 'form-group col-md-6' : 'form-group col-md-12')])
            ->attributes([
                'step' => '0.01',
            ])
            ->thousands_sep('');

        if(bpshop_show_column('product', 'sale_price')) {
            CRUD::field('sale_price')
                ->tab(__('backpack-shop::product.crud.tabs.sales'))
                ->type('number')
                ->label(__('backpack-shop::product.crud.sale_price.label'))
                ->prefix(config('backpack-shop.currency.sign', '€'))
                ->wrapper(['class' => 'form-group col-md-6'])
                ->attributes([
                    'step' => '0.01',
                ])
                ->thousands_sep('');
        }

        CRUD::field('vat_class_id')
            ->tab(__('backpack-shop::product.crud.tabs.sales'))
            ->type('select2')
            ->label(__('backpack-shop::product.crud.vat_class_id.label'))
            ->entity('vat_class')
            ->attribute('name')
            ->model(VatClass::class);

        if(bpshop_shipping_size_enabled()) {
            CRUD::field('shipping_sizes')
                ->tab(__('backpack-shop::product.crud.tabs.shipping'))
                ->type('repeatable')
                ->init_rows(0)
                ->label(__('backpack-shop::product.crud.shipping_sizes.label'))
                ->subfields([
                    [
                        'name' => 'shipping_size_id',
                        'type' => 'select2_from_array',
                        'wrapper' => ['class' => 'form-group col-md-6'],
                        'label' => __('backpack-shop::product.crud.shipping_sizes.shipping_size_id.label'),
                        'options' => ShippingSize::orderBy('lft')->pluck('name', 'id'),
                    ],
                    [
                        'name' => 'max_product_count',
                        'type' => 'number',
                        'wrapper' => ['class' => 'form-group col-md-6'],
                        'label' => __('backpack-shop::product.crud.shipping_sizes.max_product_count.label'),
                        'hint' => __('backpack-shop::product.crud.shipping_sizes.max_product_count.hint'),
                    ],
                ])
                ->reorder(true)
                ->new_item_label(__('backpack-shop::product.crud.shipping_sizes.new_item_label'));
        }

        if(bpshop_shipping_weight_enabled()) {
            CRUD::field('shipping_weight')
                ->tab(__('backpack-shop::product.crud.tabs.shipping'))
                ->type('number')
                ->label(__('backpack-shop::product.crud.shipping_weight.label'))
                ->suffix(__('backpack-shop::product.crud.shipping_weight.suffix'));
        }

        if(bpshop_show_column('product', 'variations')) {
            $subfields = [
                [
                    'name' => 'id',
                    'type' => 'text',
                    'label' => __('backpack-shop::product.crud.variations.id.label'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'description',
                    'type' => 'text',
                    'label' => __('backpack-shop::product.crud.variations.description.label'),
                    'wrapper' => ['class' => 'form-group col-md-8'],
                ],
                [
                    'name' => 'photo',
                    'type' => 'image',
                    'label' => __('backpack-shop::product.crud.variations.photo.label'),
                    'aspect_ratio' => config('eleven59.backpack-shop.product-variation-photo.aspect-ratio', 0),
                    'crop' => config('eleven59.backpack-shop.product-variation-photo.crop', true),
                    'disk' => config('eleven59.backpack-shop.product-variation-photo.disk', null),
                    'prefix' => config('eleven59.backpack-shop.product-variation-photo.prefix', null),
                ],
                [
                    'name' => 'price',
                    'type' => 'number',
                    'label' => __('backpack-shop::product.crud.variations.price.label'),
                    'hint' => __('backpack-shop::product.crud.variations.price.hint'),
                    'prefix' => config('backpack-shop.currency.sign', '€'),
                    'wrapper' => ['class' => (bpshop_show_column('product', 'sale_price') ? 'form-group col-md-6' : 'form-group col-md-12')],
                    'attributes' => [
                        'step' => '0.01',
                    ],
                    'thousands_sep' => '',
                ],
            ];

            if(bpshop_show_column('product', 'sale_price')) {
                $subfields[] = [
                    'name' => 'sale_price',
                    'type' => 'number',
                    'label' => __('backpack-shop::product.crud.variations.sale_price.label'),
                    'prefix' => config('backpack-shop.currency.sign', '€'),
                    'wrapper' => ['class' => 'form-group col-md-6'],
                    'attributes' => [
                        'step' => '0.01',
                    ],
                    'thousands_sep' => '',
                ];
            }

            CRUD::field('variations')
                ->tab(__('backpack-shop::product.crud.tabs.variations'))
                ->type('repeatable')
                ->init_rows(0)
                ->label(__('backpack-shop::product.crud.variations.label'))
                ->subfields($subfields)
                ->reorder(true)
                ->new_item_label(__('backpack-shop::product.crud.variations.new_item_label'));
        }

        /* Meta/SEO fields (also optional) */
        if(bpshop_show_column('product', 'meta-title')) {
            CRUD::field('meta_title')
                ->tab('SEO/Meta')
                ->type('text')
                ->label(__('backpack-shop::product.crud.meta-title.label'))
                ->hint(__('backpack-shop::product.crud.meta-title.hint'));
        }

        if(bpshop_show_column('product', 'meta-description')) {
            CRUD::field('meta_description')
                ->tab('SEO/Meta')
                ->type('textarea')
                ->attributes([
                    'rows' => '3',
                    'maxlength' => '160',
                    'style' => 'resize: none',
                ])
                ->label(__('backpack-shop::product.crud.meta-description.label'))
                ->hint(__('backpack-shop::product.crud.meta-description.hint'));
        }

        if(bpshop_show_column('product', 'meta-image')) {
            CRUD::field('meta_image')
                ->tab('SEO/Meta')
                ->type('image')
                ->label(__('backpack-shop::product.crud.meta-image.label'))
                ->hint(__('backpack-shop::product.crud.meta-image.hint'))
                ->aspect_ratio(config('eleven59.backpack-shop.category-meta-image.aspect-ratio', 1.91))
                ->crop(config('eleven59.backpack-shop.category-meta-image.crop', true))
                ->disk(config('eleven59.backpack-shop.category-meta-image.disk', null))
                ->prefix(config('eleven59.backpack-shop.category-meta-image.prefix', null));
        }

        if(!empty(config('eleven59.backpack-shop.product_extras', []))) {
            foreach(config('eleven59.backpack-shop.product_extras') as $name => $extra)
            {
                if(is_numeric($name)) $name = $extra['name'] ?? '';
                if(empty($name)) throw new \Exception("Please make sure all your features have a valid name. See config/mohamed7sameer/backpack6-shop.php.");

                $field = $extra;
                $field['name'] = $name;
                $field['fake'] = true;
                $field['store_in'] = 'extras';
                $field['tab'] = $extra['tab'] ?? __('backpack-shop::product.crud.tabs.extras');

                $crudField = CRUD::addField($field);
                if(!empty($field['afterField'])) {
                    $crudField->afterField($field['afterField']);
                }
                if(!empty($field['beforeField'])) {
                    $crudField->beforeField($field['beforeField']);
                }
            }
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
