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

    /**
     * Mime types browsers may execute scripts from. Served as attachments so an
     * attacker-uploaded HTML/SVG cannot run JavaScript on this origin.
     */
    private const FORCE_DOWNLOAD_MIME_TYPES = [
        'text/html',
        'application/xhtml+xml',
        'image/svg+xml',
        'application/javascript',
        'text/javascript',
        'application/x-javascript',
        'application/ecmascript',
        'text/ecmascript',
        'text/xml',
        'application/xml',
    ];

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
        // Always measure the file on disk: media-library's `size` column is written at
        // upload time, so a file replaced out of band would otherwise advertise a
        // Content-Length that does not match the streamed bytes (ERR_CONTENT_LENGTH_MISMATCH).
        $size = $disk->size($path);
        $mimeType = $assetMedia->mime_type ?? 'application/octet-stream';
        $disposition = $this->shouldForceDownload($mimeType) ? 'attachment' : 'inline';

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
            'Content-Type' => $mimeType,
            'Content-Length' => $size,
            'Content-Disposition' => $this->buildContentDisposition($disposition, $fileName),
            'Cache-Control' => 'private, no-cache',
            'X-Content-Type-Options' => 'nosniff',
            'X-Robots-Tag' => 'none',
        ]);
    }

    private function shouldForceDownload(string $mimeType): bool
    {
        $normalized = strtolower(trim(explode(';', $mimeType, 2)[0]));

        return in_array($normalized, self::FORCE_DOWNLOAD_MIME_TYPES, true);
    }

    private function buildContentDisposition(string $disposition, string $fileName): string
    {
        $safeFileName = $this->sanitizeFilenameForHeader($fileName);
        $fallback = Str::ascii($safeFileName);

        try {
            return HeaderUtils::makeDisposition($disposition, $safeFileName, $fallback);
        } catch (\InvalidArgumentException) {
            return HeaderUtils::makeDisposition($disposition, 'file', 'file');
        }
    }

    private function sanitizeFilenameForHeader(string $fileName): string
    {
        // Strip control chars and Unicode bidirectional overrides (U+202A–U+202E,
        // U+2066–U+2069, U+200E/F) so an attacker-uploaded filename cannot spoof
        // the displayed extension in the browser's download UI.
        $fileName = preg_replace('/[\x00-\x1F\x7F\x{202A}-\x{202E}\x{2066}-\x{2069}\x{200E}\x{200F}]/u', '', $fileName) ?? $fileName;

        // Symfony's makeDisposition rejects "/" and "\".
        return str_replace(['/', '\\'], '_', $fileName);
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
