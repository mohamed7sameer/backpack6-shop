<?php

namespace Eleven59\BackpackShop\Console\Commands;

use Backpack\CRUD\app\Console\Commands\Traits\PrettyCommandOutput;
use Eleven59\BackpackShop\AddonServiceProvider;
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
        $this->executeArtisanProcess('backpack:add-sidebar-content', [
            'code' => '
<li class="nav-title"><span> {{ trans("backpack-shop::sidebar.shop") }}</span></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url("product") }}"><i class="nav-icon la la-box"></i> {{ trans("backpack-shop::sidebar.products") }}</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url("product-category") }}"><i class="nav-icon la la-folder"></i>  {{ trans("backpack-shop::sidebar.categories") }}</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url("order") }}?status=paid"><i class="la la-credit-card nav-icon"></i>  {{ trans("backpack-shop::sidebar.orders") }}</a></li>

<li class="nav-title"><span> {{ trans("backpack-shop::sidebar.shop_admin") }}</span></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url("product-property") }}"><i class="nav-icon la la-list"></i>  {{ trans("backpack-shop::sidebar.product_properties") }}</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url("product-status") }}"><i class="nav-icon la la-check"></i>  {{ trans("backpack-shop::sidebar.product_statuses") }}</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url("shipping-rule") }}"><i class="nav-icon la la-balance-scale"></i>  {{ trans("backpack-shop::sidebar.shipping_rules") }}</a></li>
@if(bpshop_shipping_size_enabled())
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url("shipping-size") }}"><i class="nav-icon la la-boxes"></i>  {{ trans("backpack-shop::sidebar.shipping_sizes") }}</a></li>
@endif
<li class="nav-item"><a class="nav-link" href="{{ backpack_url("shipping-region") }}"><i class="nav-icon la la-globe"></i>  {{ trans("backpack-shop::sidebar.shipping_regions") }}</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url("vat-class") }}"><i class="nav-icon la la-money-check"></i>  {{ trans("backpack-shop::sidebar.vat_classes") }}</a></li>
', ]);
        $this->closeProgressBlock();

        // Done
        $this->infoBlock('Backpack Shop installation complete.', 'Done!');
        $this->newLine();
    }
}
