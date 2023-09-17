<?php

namespace mohamed7sameer\BackpackShop\Console\Commands;

use Backpack\CRUD\app\Console\Commands\Traits\PrettyCommandOutput;
use mohamed7sameer\BackpackShop\AddonServiceProvider;
use Illuminate\Console\Command;

class Install extends Command
{
    use PrettyCommandOutput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack-shop:install
                                {--debug} : Show process output or not. Useful for debugging.';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Backpack Shop: publish config, add sidebar content, run migrations';

    /**
     * Execute the console command.
     *
     * @return mixed Command-line output
     */
    public function handle()
    {
        $this->infoBlock('Installing:', 'Backpack Shop');

        // Publish configuration
        $this->progressBlock('Publishing configs');
        $this->executeArtisanProcess('vendor:publish', [
            '--provider' => AddonServiceProvider::class,
            '--tag' => 'config',
        ]);
        $this->closeProgressBlock();

        // Run migrations
        $this->progressBlock('Run migrations');
        $this->executeArtisanProcess('migrate', ['--no-interaction' => true]);
        $this->closeProgressBlock();

        // Add sidebar content
        $this->progressBlock('Add sidebar content');
        $this->executeArtisanProcess('backpack:add-menu-content', [
            'code' => '
<x-backpack::menu-dropdown title="Ecommerce " >

    <x-backpack::menu-dropdown-header title="{{ trans(\'backpack-shop::sidebar.shop\') }}" />
    <x-backpack::menu-dropdown-item title="{{ trans(\'backpack-shop::sidebar.products\') }}" icon="nav-icon la la-box" :link="backpack_url(\'product\')" />
    <x-backpack::menu-dropdown-item title="{{ trans(\'backpack-shop::sidebar.categories\') }}" icon="nav-icon la la-folder" :link="backpack_url(\'product-category\')" />
    <x-backpack::menu-dropdown-item title="{{ trans(\'backpack-shop::sidebar.orders\') }}" icon="la la-credit-card nav-icon" :link="backpack_url(\'order\') . \'?status=paid\'" />


    <x-backpack::menu-dropdown-header title="{{ trans(\'backpack-shop::sidebar.shop_admin\') }}" />

    <x-backpack::menu-dropdown-item title="{{ trans(\'backpack-shop::sidebar.product_properties\') }}" icon="nav-icon la la-list" :link="backpack_url(\'product-property\')" />

    <x-backpack::menu-dropdown-item title="{{ trans(\'backpack-shop::sidebar.product_statuses\') }}" icon="nav-icon la la-check" :link="backpack_url(\'product-status\')" />


    <x-backpack::menu-dropdown-item title="{{ trans(\'backpack-shop::sidebar.shipping_rules\') }}" icon="nav-icon la la-balance-scale" :link="backpack_url(\'shipping-rule\')" />


    @if(bpshop_shipping_size_enabled())
        <x-backpack::menu-dropdown-item title="{{ trans(\'backpack-shop::sidebar.shipping_sizes\') }}" icon="nav-icon la la-boxes" :link="backpack_url(\'shipping-size\')" />
    @endif
    <x-backpack::menu-dropdown-item title="{{ trans(\'backpack-shop::sidebar.shipping_regions\') }}" icon="nav-icon la la-globe" :link="backpack_url(\'shipping-region\')" />
    <x-backpack::menu-dropdown-item title="{{ trans(\'backpack-shop::sidebar.vat_classes\') }}" icon="nav-icon la la-money-check" :link="backpack_url(\'vat-class\')" />

</x-backpack::menu-dropdown>
', ]);
        $this->closeProgressBlock();

        // Done
        $this->infoBlock('Backpack Shop installation complete.', 'Done!');
        $this->newLine();
    }
}
