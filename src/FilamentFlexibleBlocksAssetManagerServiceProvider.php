<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset as AssetModel;
use Statikbe\FilamentFlexibleBlocksAssetManager\Testing\TestsLaravelFilamentFlexibleBlocksAssetManager;

class FilamentFlexibleBlocksAssetManagerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'laravel-filament-flexible-blocks-asset-manager';

    public static string $viewNamespace = 'laravel-filament-flexible-blocks-asset-manager';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('statikbe/laravel-filament-flexible-blocks-asset-manager');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath("/../routes/{$configFileName}.php"))) {
            $package->hasRoutes($this->getRoutes());
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/laravel-filament-flexible-blocks-asset-manager/{$file->getFilename()}"),
                ], 'laravel-filament-flexible-blocks-asset-manager-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsLaravelFilamentFlexibleBlocksAssetManager());

        //add Asset to morph map when used:
        if (Relation::requiresMorphMap()) {
            Relation::morphMap([
                'filament-flexible-blocks-asset-manager::asset' => AssetModel::class,
            ], true);
        }
    }

    protected function getAssetPackageName(): ?string
    {
        return 'statikbe/laravel-filament-flexible-blocks-asset-manager';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('laravel-filament-flexible-blocks-asset-manager', __DIR__ . '/../resources/dist/components/laravel-filament-flexible-blocks-asset-manager.js'),
            //Css::make('laravel-filament-flexible-blocks-asset-manager-styles', __DIR__ . '/../resources/dist/laravel-filament-flexible-blocks-asset-manager.css'),
            //Js::make('laravel-filament-flexible-blocks-asset-manager-scripts', __DIR__ . '/../resources/dist/laravel-filament-flexible-blocks-asset-manager.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [

        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [
            'heroicon-o-photo',
        ];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [
            'filament-flexible-blocks-asset-manager',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_assets_table',
        ];
    }
}
