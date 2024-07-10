<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;
use Statikbe\FilamentFlexibleBlocksAssetManager\Http\Controllers\AssetController;

$assetRoutePrefix = FilamentFlexibleBlocksAssetManagerConfig::getAssetRoutePrefix();
$assetRoutePrefix = Str::replaceEnd('/', '', $assetRoutePrefix);

Route::get("$assetRoutePrefix/{asset}/{locale?}", [AssetController::class, 'index'])
    ->middleware(['web'])
    ->name('filament-flexible-blocks-asset-manager.asset_index');
