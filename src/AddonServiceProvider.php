<?php

namespace Eleven59\BackpackShop;

use Eleven59\BackpackShop\Console\Commands\Install;
use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'eleven59';
    protected $packageName = 'backpack-shop';
    protected $commands = [
        Install::class,
    ];
}
