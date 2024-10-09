<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentFlexibleBlocksAssetManagerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'laravel-filament-flexible-blocks-asset-manager';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            FilamentFlexibleBlocksAssetManagerConfig::getResource(),
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
