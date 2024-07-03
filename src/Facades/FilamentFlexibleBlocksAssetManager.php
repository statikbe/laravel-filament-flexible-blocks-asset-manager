<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManager
 */
class FilamentFlexibleBlocksAssetManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManager::class;
    }
}
