<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource;

class FilamentFlexibleBlocksAssetManagerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'laravel-filament-flexible-blocks-asset-manager';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            AssetResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
