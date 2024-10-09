<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;
use Statikbe\FilamentFlexibleBlocksAssetManager\Http\Controllers\AssetController;

$assetMiddleware = FilamentFlexibleBlocksAssetManagerConfig::getAssetMiddleware() ? ['web', FilamentFlexibleBlocksAssetManagerConfig::getAssetMiddleware()] : ['web'];
$assetRoutePrefix = FilamentFlexibleBlocksAssetManagerConfig::getAssetRoutePrefix();
$assetRoutePrefix = Str::replaceEnd('/', '', $assetRoutePrefix);

Route::get("$assetRoutePrefix/{assetId}/{locale?}", [AssetController::class, 'index'])
    ->middleware($assetMiddleware)
    ->name('filament-flexible-blocks-asset-manager.asset_index');
