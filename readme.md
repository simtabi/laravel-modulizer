![banner](assets/banner.png?raw=true)

This package provides you with a simple way to generate a new Laravel module(package) template and, it will let you focus on the development of the package instead of the boilerplate with a
[Laravel Modules](https://github.com/nWidart/laravel-modules) experience. 
If you like a visual explanation [check out this video by Jeffrey Way on Laracasts](https://laracasts.com/series/building-laracasts/episodes/3). 

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

#### 1. Used in filenames:

`Module` = Module name ie `Contacts`

`module` = Module name in lowercase ie `contacts`

`Model` = Model name ie `Contact`

`model` = Model name in lowercase ie `contact`

> For a folder called `Models` rename it to `Entities` it will be renamed when back to Models when generating a new module.

#### 2. Only used inside files:


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


## Available commands

### Generate
**Command:**
```bash
$ php artisan modulizer:module:generate my-vendor my-package
```

**Result:**
The command will handle practically everything for you. It will create a packages directory, creates the vendor and package directory in it, pulls in a skeleton package, sets up composer.json and creates a service provider.

**Options:**
```bash
$ php artisan modulizer:module:generate my-vendor my-package --i
$ php artisan modulizer:module:generate --i
```
The package will be created interactively, allowing to configure everything in the package's `composer.json`, such as the license and package description.

```bash
$ php artisan modulizer:module:generate my-vendor/my-package
```
Alternatively you may also define your vendor and name with a forward slash instead of a space.

### Tests
**Command:**
```bash
$ php artisan modulizer:module:tests
```

**Result:**
Modulizer will go through all maintaining packages (in `platform/modules/`) and publish their tests to `tests/modules`.
Add the following to phpunit.xml (under the other testsuites) in order to run the tests from the packages:
```xml
<testsuite name="Packages">
    <directory suffix="Test.php">./tests/modules</directory>
</testsuite>
```

**Options:**
```bash
$ php artisan modulizer:module:tests my-vendor my-package
```

**Remarks:**
If a tests folder exists, the files will be copied to a dedicated folder in the Laravel App tests folder. This allows you to use all of Laravel's own testing functions without any hassle.

### List
**Command:**
```bash
$ php artisan modulizer:module:list
```

**Result:**
An overview of all packages in the `/modules` directory.

**Options:**
```bash
$ php artisan modulizer:module:list --git
```
The packages are displayed with information on the git status (branch, commit difference with origin) if it is a git repository.

### Remove
**Command:**
```bash
$ php artisan modulizer:module:remove my-vendor my-package
```

**Result:**
The `my-vendor\my-package` package is deleted, including its references in `composer.json` and `config/app.php`.

### Publish
**Command:**
```bash
$ php artisan modulizer:module:publish my-vendor my-package https://github.com/my-vendor/my-package
```

**Result:**
The `my-vendor\my-package` package will be published to Github using the provided url.

### Check
**Command:**
```bash
$ php artisan modulizer:module:check my-vendor my-package
```

**Result:**
The `my-vendor\my-package` package will be checked for security vulnerabilities using SensioLabs security checker.

**Remarks**
You first need to run

```bash
$ composer require sensiolabs/security-checker
```

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


## Credits & Inspiration

- [Simtabi][link-author]
- [nWidart](https://github.com/nWidart/laravel-modules)
- [dcblogdev](https://github.com/dcblogdev/laravel-modulizer)
- [Jeroen-G](https://github.com/Jeroen-G/laravel-packager)
- [All Contributors][link-contributors]

## License

Please see the [license file][6] for more information.

[link-author]: https://github.com/simtabi
[link-contributors]: contributors

[3]:    changelog.md
[4]:    https://github.com/simtabi/laravel-modulizer
[5]:    http://semver.org/
[6]:    license.md
