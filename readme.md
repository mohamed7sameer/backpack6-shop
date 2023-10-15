# BackpackShop

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

تضيف هذه الحزم نماذج قابلة للتخصيص ولوحات CRUD ووظائف الطلب لإنشاء متجر ويب باستخدامها
 [Backpack for Laravel](https://backpackforlaravel.com). 

Functionality:
- Create product categories and products
- Add custom product attributes and statuses
- Process orders and payments
- Customizable shipping rules based on location, package size and/or weight
- Fully featured CRUD panels for all of the above
- Shopping cart helper
- Automated installer
- Fully translatable CRUD using default lang files (EN and NL included)

## Requirements

First, make sure you are running `PHP 8.1+` and have installed:

- Backpack (`5.x`) ([install guide](https://backpackforlaravel.com/docs/5.x/installation))
- Backpack Pro (sorry, it's required for now; see [pricing](https://backpackforlaravel.com/pricing))

This package was tested extensively with Laravel 8 and Backpack 5. It seemed fine with Laravel 9 and 10, and Backpack 6 as well, but no guarantees (yet). I will be updating the package for Backpack 6 compatibility, but I have no idea on the ETA for that.

I'm also planning to make Backpack Pro optional, so it will be more accessible. Again, no ETA.

## Installation

### Step 1 - require package

Install the package via Composer

```bash
composer require mohamed7sameer/backpack6-shop
```

### Step 2 - run installer

Then run the installer, which publishes the config file, runs all required migrations, and adds shop items to the sidebar_contents file.

```bash
php artisan backpack-shop:install
```

### Step 3 - config

Please check `config/mohamed7sameer/backpack6-shop.php` since some things will need to be configured for your particular use case.

### Step 4 - minimum requirements

After updating the config file, almost everything should run out of the box, but a couple of things will need to be done in the Backpack Admin to use the package effectively (i.e., prevent unexpected errors). The bare minimum is:

- Define at least one VAT class in the `VAT classes` CRUD panel
- Define at least one package size (if using, see config) using the `Shipping sizes` CRUD panel
- Create at least one shipping rule on the `Shipping rules` CRUD panel
- Maybe add `Categories`, and some `Products`? It's a webshop package after all.

### Step 5 (optional but recommened) - pick or build payment provider

This package does not come with a payment provider included. It does, however, come with a "No payment" provider. This is the default, and works in all cases where customers don't have to pay online (i.e., they pay using bank transfer, you only offer pay and collect, or everything in the store is free).

If you do need a payment provider, I have written one for [Mollie](https://mollie.com), which you can find here: [mohamed7sameer/backpack6-shop-mollie](https://github.com/mohamed7sameer/backpack6-shop-mollie). Install instructions and how to configure are also in the readme for that package.

It's also relatively easy to write your own if you already have code to talk with your payment provider of choice and only need to integrate it with the shopping cart and checkout functionality of this package. See [payment-providers.md](./docs/payment-providers.md) for the documentation.

## Usage

See [docs/usage.md](./docs/usage.md).

## Change log

Changes are documented here on Github. Please browse the commit history.

Breaking changes are be listed here:

### 0.2.0

- 0.1.x used a single category per product. This version adds a pivot table which replaces this functionality. This means you will have to re-enter all categories manually (or write a quick script to do this automatically). It also means that if you are using $product->category anywhere, this no longer works and will generate an error.

## Testing

This package provides no testing.

## Contributing

Please see [contributing.md](contributing.md) for a todolist and howtos.

## Security

If you discover any security related issues, please send an email to [info@mohammed-samir.com](mailto:info@mohammed-samir.com) instead of using the issue tracker.

## Credits

- Author: [mohammed-samir.com](https://mohammed-samir.com) (github: [mohamed7sameer](https://github.com/mohamed7sameer))
- Built with [Backpack for Laravel](https://backpackforlaravel.com). Special thanks to [Cristian Tăbăcitu](https://github.com/tabacitu) and the rest of the Backpack team for the awesome work.

## License

This project was released under MIT, so you can install it on top of any Backpack & Laravel project. Please see the [license file](license.md) for more information. 

However, please note that you do need Backpack Pro (note: 5.x) installed, which is proprietary software. Please refer to their [pricing](https://backpackforlaravel.com/pricing) page to get started.

A version that does not require Backpack Pro but only the open core source is planned, but will probably not be released for a while.


[ico-version]: https://img.shields.io/packagist/v/mohamed7sameer/backpack6-shop.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/mohamed7sameer/backpack6-shop.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/mohamed7sameer/backpack6-shop
[link-downloads]: https://packagist.org/packages/mohamed7sameer/backpack6-shop
