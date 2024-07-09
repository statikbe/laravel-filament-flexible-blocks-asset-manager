<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager;

class FilamentFlexibleBlocksAssetManagerConfig
{
    public static function hasTranslatableAssets(): bool
    {
        return config('filament-flexible-blocks-asset-manager.translatable_assets', false);
    }

    public static function getAssetAuthorisationGate(): ?string
    {
        return config('filament-flexible-blocks-asset-manager.asset_authorisation.gate');
    }

    public static function getAssetAuthorisationPolicy(): ?string
    {
        return config('filament-flexible-blocks-asset-manager.asset_authorisation.policy');
    }

    public static function getStorageDisk(): ?string
    {
        return config('filament-flexible-blocks-asset-manager.storage_disk');
    }

    public static function getStorageDirectory(): ?string
    {
        return config('filament-flexible-blocks-asset-manager.storage_directory');
    }

    public static function getStorageVisibility(): ?string
    {
        return config('filament-flexible-blocks-asset-manager.storage_visibility') ?? 'public';
    }

    public static function getAcceptedFileTypes(): array
    {
        return config('filament-flexible-blocks-asset-manager.accepted_file_types') ?? [];
    }

    public static function getImageEditor(): array|null
    {
        return config('filament-flexible-blocks-asset-manager.image_editor', null);
    }

    public static function getNavigationGroup(): ?string
    {
        return config('filament-flexible-blocks-asset-manager.navigation_group');
    }
}
