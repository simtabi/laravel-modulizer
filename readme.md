![banner](assets/banner.png?raw=true)

Laravel package for generating [Laravel Modules](https://github.com/nWidart/laravel-modules) from a template. 

# Requirements

Laravel 8 or 9 and PHP 8.0

# Install

You can install the package via composer:

```bash
composer require simtabi/laravel-modulizer
```

Publish both the `config` and `stubs`:

```bash
php artisan vendor:publish --provider="Simtabi\Modulizer\ModulizerServiceProvider"
```

This will publish a `modulizer.php` config file

This contains:
```php
    'ignore_files' => ['module.json'],
    'modules_path' => env('MODULIZER_MODULES_PATH', 'platform/modules'),
    'stubs_path'   => env('MODULIZER_STUBS_PATH', 'stubs/module'),
```
By default, the stubs will be located at ``path-to-vendor-package-folder/stubs/modulizer`` you can add your own paths by adding your paths to your .env file or changing the config file.

```bash 
MODULIZER_MODULES_PATH=your-custom-modules-destination-location
MODULIZER_STUBS_PATH=your-custom-stubs-location
```

# Usage

Create or update the stubs file. The filename and contents should have placeholders for example `ModulesController` will be replaced with your name + Controller. ie `ContactsController` when the command is executed.

Placeholders:

These placeholders are replaced with the name provided when running `php artisan modulizer:module:build`

Used in filenames:

`Module` = Module name ie `Contacts`

`module` = Module name in lowercase ie `contacts`

`Model` = Model name ie `Contact`

`model` = Model name in lowercase ie `contact`

> For a folder called `Models` rename it to `Entities` it will be renamed when back to Models when generating a new module.

Only used inside files:


`{Module}` = Module name ie `PurchaseOrders`

`{module}` = Module name in lowercase ie `purchaseOrder`

`{module_}` = module name with underscores ie `purchase_orders`

`{module-}` = module name with hyphens ie `purchase-orders`

`{module }` = module name puts space between capital letters ie `PurchaseOrders` becomes `Purchase Orders`

`{Model}` = Model name ie `PurchaseOrder`

`{model}` = Model name in lowercase ie `purchaseOrder`

`{model_}` = model name with underscores ie `purchase_orders`

`{model-}` = model name with hyphens ie `purchase-orders`

`{model }` = model name puts space between capital letters ie `PurchaseOrder` becomes `Purchase Order`

## Change log

Please see the [changelog][3] for more information on what has changed recently.

## Contributing

Contributions are welcome and will be fully credited.

Contributions are accepted via Pull Requests on [Github][4].

## Pull Requests

- **Document any change in behaviour** - Make sure the `readme.md` and any other relevant documentation are kept up-to-date.

- **Consider our release cycle** - We try to follow [SemVer v2.0.0][5]. Randomly breaking public APIs is not an option.

- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.

## Security

If you discover any security related issues, please email imani@simtabi.com email instead of using the issue tracker.

## License

Please see the [license file][6] for more information.


## Inspiration

- [nWidart](https://github.com/nWidart/laravel-modules)
- [dcblogdev](https://github.com/dcblogdev/laravel-modulizer)

[3]:    changelog.md
[4]:    https://github.com/simtabi/laravel-modulizer
[5]:    http://semver.org/
[6]:    license.md
