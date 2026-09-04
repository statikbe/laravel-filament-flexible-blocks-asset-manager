<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

class FilamentFlexibleBlocksAssetManager
{
    /**
     * Find the configured asset model by its ID.
     *
     * @throws ModelNotFoundException when no asset with the given ID exists.
     */
    public function findAsset(string | int $assetId): Asset
    {
        return FilamentFlexibleBlocksAssetManagerConfig::getModel()::findOrFail($assetId);
    }
}
