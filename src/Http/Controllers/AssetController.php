<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
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

        $assetMedia = $this->getAssetMedia($asset, $locale);

        if (! $assetMedia) {
            abort(Response::HTTP_NOT_FOUND, trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.error.asset_media_not_found'));
        }

        return $assetMedia
            ->setCustomHeaders([
                'X-Robots-Tag' => 'none', //equivalent to noindex, nofollow.
            ]);
    }

    private function getAssetMedia(InteractsWithMedia $asset, ?string $locale = null): ?Media
    {
        //TODO conversions
        $assetMedia = null;
        if (! $locale && FilamentFlexibleBlocksAssetManagerConfig::hasTranslatableAssets()) {
            $locale = app()->getLocale();
            $filters = ['locale' => $locale];
            $assetMedia = $asset->getFirstMedia($asset->getAssetCollection(), $filters);
        }

        if (! $assetMedia) {
            $assetMedia = $asset->getFirstMedia($asset->getAssetCollection());
        }

        return $assetMedia;
    }
}
