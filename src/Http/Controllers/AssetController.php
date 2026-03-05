<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Conversions\ConversionCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGeneratorFactory;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        // Resolve disk, path, and mime type for the file being served
        $disk = $conversion ? $assetMedia->conversions_disk : $assetMedia->disk;
        $path = $this->getMediaPath($assetMedia, $conversion);
        $mimeType = $conversion
            ? (Storage::disk($disk)->mimeType($path) ?: $assetMedia->mime_type)
            : $assetMedia->mime_type;

        // Get download filename (file name or custom file name)
        $downloadFileName = $asset->getDownloadFileName();

        if ($downloadFileName || $conversion) {
            return $this->createStreamedResponse($disk, $path, $mimeType, $downloadFileName);
        }

        return $assetMedia
            ->setCustomHeaders([
                'X-Robots-Tag' => 'none', // equivalent to noindex, nofollow.
            ]);
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

    private function getMediaPath(Media $media, ?string $conversion): string
    {
        if ($conversion) {
            $pathGenerator = PathGeneratorFactory::create($media);
            $conversionObj = ConversionCollection::createForMedia($media)->getByName($conversion);

            return $pathGenerator->getPathForConversions($media) . $conversionObj->getConversionFile($media);
        }

        return $media->getPathRelativeToRoot();
    }

    private function createStreamedResponse(string $disk, string $path, string $mimeType, ?string $downloadFileName = null): StreamedResponse
    {
        $headers = [
            'Content-Type' => $mimeType,
            'X-Robots-Tag' => 'none',
        ];

        if ($downloadFileName) {
            $headers['Content-Disposition'] = 'inline; filename="' . $downloadFileName . '"';
        }

        return new StreamedResponse(function () use ($disk, $path) {
            $stream = Storage::disk($disk)->readStream($path);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, $headers);
    }
}
