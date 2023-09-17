<?php

namespace mohamed7sameer\BackpackShop;

use mohamed7sameer\BackpackShop\Console\Commands\Install;
use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'mohamed7sameer';
    protected $packageName = 'backpack-shop';
    protected $commands = [
        Install::class,
    ];
}
