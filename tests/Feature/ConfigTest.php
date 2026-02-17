<?php

use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

it('returns default translatable assets value', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::hasTranslatableAssets())->toBeTrue();
});

it('returns overridden translatable assets value', function () {
    config()->set('filament-flexible-blocks-asset-manager.translatable_assets', false);

    expect(FilamentFlexibleBlocksAssetManagerConfig::hasTranslatableAssets())->toBeFalse();
});

it('returns default asset authorisation gate', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::getAssetAuthorisationGate())->toBeNull();
});

it('returns overridden asset authorisation gate', function () {
    config()->set('filament-flexible-blocks-asset-manager.asset_authorisation.gate', 'my-gate');

    expect(FilamentFlexibleBlocksAssetManagerConfig::getAssetAuthorisationGate())->toBe('my-gate');
});

it('returns default asset authorisation policy', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::getAssetAuthorisationPolicy())->toBeNull();
});

it('returns default asset middleware', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::getAssetMiddleware())->toBeNull();
});

it('returns default storage disk', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::getStorageDisk())->toBeNull();
});

it('returns default storage directory', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::getStorageDirectory())->toBeNull();
});

it('returns default storage visibility', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::getStorageVisibility())->toBe('public');
});

it('returns public when storage visibility is null', function () {
    config()->set('filament-flexible-blocks-asset-manager.storage_visibility', null);

    expect(FilamentFlexibleBlocksAssetManagerConfig::getStorageVisibility())->toBe('public');
});

it('returns default accepted file types', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::getAcceptedFileTypes())->toBe([]);
});

it('returns overridden accepted file types', function () {
    config()->set('filament-flexible-blocks-asset-manager.accepted_file_types', ['application/pdf']);

    expect(FilamentFlexibleBlocksAssetManagerConfig::getAcceptedFileTypes())->toBe(['application/pdf']);
});

it('returns default image editor config', function () {
    $editor = FilamentFlexibleBlocksAssetManagerConfig::getImageEditor();

    expect($editor)->toBeArray()
        ->and($editor['enabled'])->toBeFalse();
});

it('returns default navigation group from translation', function () {
    config()->set('filament-flexible-blocks-asset-manager.navigation_group', null);

    $result = FilamentFlexibleBlocksAssetManagerConfig::getNavigationGroup();

    expect($result)->toBeString();
});

it('returns overridden navigation group', function () {
    config()->set('filament-flexible-blocks-asset-manager.navigation_group', 'My Group');

    expect(FilamentFlexibleBlocksAssetManagerConfig::getNavigationGroup())->toBe('My Group');
});

it('returns default navigation sort', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::getNavigationSort())->toBeNull();
});

it('returns default model class', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::getModel())->toBe(Asset::class);
});

it('returns default resource class', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::getResource())->toBe(AssetResource::class);
});

it('returns default asset route prefix', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::getAssetRoutePrefix())->toBe('/asset');
});

it('returns overridden asset route prefix', function () {
    config()->set('filament-flexible-blocks-asset-manager.asset_route_prefix', '/files');

    expect(FilamentFlexibleBlocksAssetManagerConfig::getAssetRoutePrefix())->toBe('/files');
});

it('returns empty array when accepted file types is null', function () {
    config()->set('filament-flexible-blocks-asset-manager.accepted_file_types', null);

    expect(FilamentFlexibleBlocksAssetManagerConfig::getAcceptedFileTypes())->toBe([]);
});
