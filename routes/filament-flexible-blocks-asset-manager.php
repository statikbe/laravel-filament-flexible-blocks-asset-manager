<?php

use Illuminate\Support\Facades\Route;
use Statikbe\FilamentFlexibleBlocksAssetManager\Http\Controllers\AssetController;


Route::get('/asset/{asset}/{locale?}', [AssetController::class, 'index'])
    ->middleware(['web'])
    ->name('filament-flexible-blocks-asset-manager.asset_index');
