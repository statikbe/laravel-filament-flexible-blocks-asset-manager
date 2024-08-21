<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;

class AssetController
{
    public function index(string $assetId, ?string $locale = null)
    {
        $asset = FilamentFlexibleBlocksAssetManagerConfig::getModel()::findOrFail($assetId);

        //check if a gate needs to be applied:
        $authGate = FilamentFlexibleBlocksAssetManagerConfig::getAssetAuthorisationGate();
        if ($authGate) {
            if (! Gate::allows($authGate, $asset)) {
                abort(Response::HTTP_FORBIDDEN);
            }
        }

        //TODO conversions
        $filters = [];
        if ($locale) {
            $filters = ['locale' => $locale];
        }

        return $asset
            ->getFirstMedia($asset->getAssetCollection(), $filters)
            ->setCustomHeaders([
                'X-Robots-Tag' => 'none', //equivalent to noindex, nofollow.
            ]);
    }
}
