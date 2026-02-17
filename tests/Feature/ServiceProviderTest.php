<?php

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

it('registers asset in morph map', function () {
    $morphMap = Relation::morphMap();

    expect($morphMap)->toHaveKey('filament-flexible-blocks-asset-manager::asset')
        ->and($morphMap['filament-flexible-blocks-asset-manager::asset'])->toBe(Asset::class);
});

it('loads the config file', function () {
    expect(config('filament-flexible-blocks-asset-manager'))->toBeArray()
        ->and(config('filament-flexible-blocks-asset-manager.model'))->toBe(Asset::class);
});

it('registers routes', function () {
    expect(Route::has('filament-flexible-blocks-asset-manager.asset_index'))->toBeTrue();
});

it('loads translations', function () {
    $translation = trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.asset_lbl');

    expect($translation)->not->toBe('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.asset_lbl');
});
