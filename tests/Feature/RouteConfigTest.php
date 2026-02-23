<?php

use Illuminate\Support\Facades\Route;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;

it('registers the asset index route', function () {
    expect(Route::has('filament-flexible-blocks-asset-manager.asset_index'))->toBeTrue();
});

it('route responds to GET requests', function () {
    $route = Route::getRoutes()->getByName('filament-flexible-blocks-asset-manager.asset_index');

    expect($route->methods())->toContain('GET');
});

it('route has correct URI pattern', function () {
    $route = Route::getRoutes()->getByName('filament-flexible-blocks-asset-manager.asset_index');

    expect($route->uri())->toBe('asset/{assetId}/{locale?}');
});

it('route has correct parameters', function () {
    $route = Route::getRoutes()->getByName('filament-flexible-blocks-asset-manager.asset_index');

    expect($route->parameterNames())->toContain('assetId')
        ->toContain('locale');
});

it('locale parameter is optional', function () {
    $route = Route::getRoutes()->getByName('filament-flexible-blocks-asset-manager.asset_index');

    expect($route->uri())->toContain('{locale?}');
});

it('applies web middleware', function () {
    $route = Route::getRoutes()->getByName('filament-flexible-blocks-asset-manager.asset_index');

    expect($route->middleware())->toContain('web');
});

it('generates correct URL for asset', function () {
    $url = route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => 42]);

    expect($url)->toEndWith('/asset/42');
});

it('generates correct URL with locale', function () {
    $url = route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => 42, 'locale' => 'nl']);

    expect($url)->toEndWith('/asset/42/nl');
});

it('config returns default route prefix', function () {
    expect(FilamentFlexibleBlocksAssetManagerConfig::getAssetRoutePrefix())->toBe('/asset');
});

it('config returns custom route prefix', function () {
    config()->set('filament-flexible-blocks-asset-manager.asset_route_prefix', '/files');

    expect(FilamentFlexibleBlocksAssetManagerConfig::getAssetRoutePrefix())->toBe('/files');
});
