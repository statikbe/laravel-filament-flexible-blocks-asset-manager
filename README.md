<img src="documentation/screenshots/banner.jpg" alt="Laravel Filament Flexible Blocks Asset Manager banner" width="100%" />

# Laravel Filament Flexible Blocks Asset Manager

[![Latest Version on Packagist](https://img.shields.io/packagist/v/statikbe/laravel-filament-flexible-blocks-asset-manager.svg?style=flat-square)](https://packagist.org/packages/statikbe/laravel-filament-flexible-blocks-asset-manager)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/statikbe/laravel-filament-flexible-blocks-asset-manager/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/statikbe/laravel-filament-flexible-blocks-asset-manager/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/statikbe/laravel-filament-flexible-blocks-asset-manager/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/statikbe/laravel-filament-flexible-blocks-asset-manager/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/statikbe/laravel-filament-flexible-blocks-asset-manager.svg?style=flat-square)](https://packagist.org/packages/statikbe/laravel-filament-flexible-blocks-asset-manager)

This package provides a simple document and image manager for the [Laravel Filament Flexible Content Blocks](https://github.com/statikbe/laravel-filament-flexible-content-blocks) package.

The key features are:

- a simple asset model 
- integration with the call-to-action features of the [Laravel Filament Flexible Content Blocks](https://github.com/statikbe/laravel-filament-flexible-content-blocks) package.
- basic filament CRUD UI
- routing to retrieve the assets

## Installation

You can install the package via composer:

```bash
composer require statikbe/laravel-filament-flexible-blocks-asset-manager
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-flexible-blocks-asset-manager-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-flexible-blocks-asset-manager-config"
```

Optionally, you can publish the translations using

```bash
php artisan vendor:publish --tag="filament-flexible-blocks-asset-manager-translations"
```

### Setup with the Filament Flexible Content Blocks package

To integrate the plugin with Filament, you need to add it a panel in the filament service provider. See the code sample below:

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        //...
        ->plugins([
            SpatieLaravelTranslatablePlugin::make()
                ->defaultLocales(['nl']),
            //...
            FilamentFlexibleBlocksAssetManagerPlugin::make()
        ]);
}
```

Then to use the asset manager in the call-to-actions you have to add them to the configuration of the Filament Flexible 
Content Blocks package:

```php
return [
    //...
     
    'call_to_action_models' => [
        [
            'model' => \Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset::class,
            'call_to_action_type' => \Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Form\Fields\Blocks\Type\AssetCallToActionType::class,
        ],
        //...
        //\App\Models\TranslatablePage::class,
    ],
        
    //...
]
```

## Configuration

The configuration file is [filament-flexible-blocks-asset-manager.php](config%2Ffilament-flexible-blocks-asset-manager.php)
fully commented, to explain each configuration option.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sten Govaerts](https://github.com/sten)
- [Aurel Demiri](https://github.com/AurelDemiri)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
