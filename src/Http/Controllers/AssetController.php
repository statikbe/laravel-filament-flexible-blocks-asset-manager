<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Http\Controllers;


use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

class AssetController
{
    public function index(Asset $asset, string $locale=null)
    {
        //check if a gate needs to be applied:
        $authGate = FilamentFlexibleBlocksAssetManagerConfig::getAssetAuthorisationGate();
        if($authGate) {
            if (!Gate::allows($authGate, $asset)) {
                abort(Response::HTTP_FORBIDDEN);
            }
        }

        //check if a policy needs to be applied:
        if(FilamentFlexibleBlocksAssetManagerConfig::getAssetAuthorisationPolicy()) {
            Gate::authorize('view', $asset);
        }

        //TODO conversions
        $filters = [];
        if($locale){
            $filters = ['locale' => $locale];
        }

        return $asset->getFirstMedia($asset->getAssetCollection(), $filters);
    }
}
