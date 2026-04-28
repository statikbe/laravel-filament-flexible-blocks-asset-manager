<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssetController
{
    private const STREAM_CHUNK_SIZE = 1024 * 1024;

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

        if (! $assetMedia) {
            abort(Response::HTTP_NOT_FOUND, trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.error.asset_media_not_found'));
        }

        // Resolve conversion (silently ignore invalid/ungenerated conversions)
        $conversion = $request->query('conversion');
        if ($conversion && ! $assetMedia->hasGeneratedConversion($conversion)) {
            $conversion = null;
        }

        $diskName = $conversion ? $assetMedia->conversions_disk : $assetMedia->disk;
        $path = $assetMedia->getPathRelativeToRoot($conversion ?? '');
        $disk = Storage::disk($diskName);

        if (! $disk->exists($path)) {
            abort(Response::HTTP_NOT_FOUND, trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.error.asset_media_not_found'));
        }

        // Spatie's buildResponse() calls feof() on the stream without a null guard,
        // so a flysystem adapter returning false here would fatal mid-response.
        $stream = $disk->readStream($path);
        if (! is_resource($stream)) {
            abort(Response::HTTP_NOT_FOUND, trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.error.asset_media_not_found'));
        }

        $fileName = $asset->getDownloadFileName() ?: $assetMedia->file_name;
        $size = $conversion ? $disk->size($path) : $assetMedia->size;

        return new StreamedResponse(function () use ($stream) {
            try {
                while (! feof($stream)) {
                    echo fread($stream, self::STREAM_CHUNK_SIZE);
                    flush();
                }
            } finally {
                if (is_resource($stream)) {
                    fclose($stream);
                }
            }
        }, Response::HTTP_OK, [
            'Content-Type' => $assetMedia->mime_type,
            'Content-Length' => $size,
            'Content-Disposition' => $this->buildContentDisposition($fileName),
            'Cache-Control' => 'private, no-cache',
            'X-Robots-Tag' => 'none',
        ]);
    }

    private function buildContentDisposition(string $fileName): string
    {
        $safeFileName = str_replace(['/', '\\'], '_', $fileName);

        return HeaderUtils::makeDisposition('inline', $safeFileName, Str::ascii($safeFileName));
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
