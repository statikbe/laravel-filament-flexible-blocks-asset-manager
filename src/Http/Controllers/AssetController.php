<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

class AssetController
{
    public function index(Request $request, string $assetId, ?string $locale = null)
    {
        $asset = FilamentFlexibleBlocksAssetManagerConfig::getModel()::findOrFail($assetId);

        // check if a gate needs to be applied:
        $authGate = FilamentFlexibleBlocksAssetManagerConfig::getAssetAuthorisationGate();
        if ($authGate) {
            if (! Gate::allows($authGate, $asset)) {
                abort(Response::HTTP_FORBIDDEN);
            }
        }

        $assetMedia = $this->getAssetMedia($asset, $locale);

        if (! $assetMedia || ! Storage::disk($assetMedia->disk)->exists($assetMedia->getPathRelativeToRoot())) {
            abort(Response::HTTP_NOT_FOUND, trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.error.asset_media_not_found'));
        }

        // Resolve conversion (silently ignore invalid/ungenerated conversions)
        $conversion = $request->query('conversion');
        if ($conversion && ! $assetMedia->hasGeneratedConversion($conversion)) {
            $conversion = null;
        }

        $downloadFileName = $asset->getDownloadFileName();
        if ($downloadFileName) {
            $assetMedia->file_name = $downloadFileName;
        }

        return $assetMedia
            ->toInlineResponse($request, $conversion ?? '')
            ->withHeaders(['X-Robots-Tag' => 'none']);

    }

    private function getAssetMedia(Asset $asset, ?string $locale = null): ?Media
    {
        if (! $locale && FilamentFlexibleBlocksAssetManagerConfig::hasTranslatableAssets()) {
            $locale = app()->getLocale();
        }

        // first try with locale
        $assetMedia = $asset->getFirstMedia($asset->getAssetCollection(), ['locale' => $locale]);

        if (! $assetMedia) {
            // if no media with locale try fallback:
            $assetMedia = $asset->getFirstMedia($asset->getAssetCollection());
        }

        return $assetMedia;
    }
}
