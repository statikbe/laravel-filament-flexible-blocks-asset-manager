<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager;

class FilamentFlexibleBlocksAssetManagerConfig
{
    public static function hasTranslatableAssets(): bool
    {
        return self::getConfig('translatable_assets', false);
    }

    public static function getAssetAuthorisationGate(): ?string
    {
        return self::getConfig('asset_authorisation.gate', 'view');
    }

    public static function getAssetAuthorisationPolicy(): ?string
    {
        return self::getConfig('asset_authorisation.policy');
    }

    public static function getAssetMiddleware(): ?string
    {
        return self::getConfig('asset_authorisation.middleware');
    }

    public static function getStorageDisk(): ?string
    {
        return self::getConfig('storage_disk');
    }

    public static function getStorageDirectory(): ?string
    {
        return self::getConfig('storage_directory');
    }

    public static function getStorageVisibility(): ?string
    {
        return self::getConfig('storage_visibility') ?? 'public';
    }

    public static function getAcceptedFileTypes(): array
    {
        return self::getConfig('accepted_file_types') ?? [];
    }

    public static function getImageEditor(): ?array
    {
        return self::getConfig('image_editor', null);
    }

    public static function getNavigationGroup(): ?string
    {
        return self::getConfig('navigation_group') ?? trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.navigation_group');
    }

    public static function getModel(): ?string
    {
        return self::getConfig('model');
    }

    public static function getResource(): ?string
    {
        return self::getConfig('resource');
    }

    public static function getAssetRoutePrefix(): ?string
    {
        return self::getConfig('asset_route_prefix', '/asset');
    }

    public static function getConfig($key = null, $default = null)
    {
        return config('filament-flexible-blocks-asset-manager.' . $key, $default);
    }
}
