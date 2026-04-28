<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasTranslatedMediaTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasTranslatableMedia;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable;

/**
 * @property string $name
 * @property string|null $custom_file_name
 * @property bool $use_custom_file_name
 */
class Asset extends Model implements HasMedia, HasTranslatableMedia, Linkable
{
    use HasTranslatedMediaTrait;
    use HasTranslations;
    use InteractsWithMedia;

    const MEDIA_COLLECTION_ASSETS = 'assets';

    public $translatable = ['name', 'custom_file_name'];

    public $guarded = [];

    protected $casts = [
        'use_custom_file_name' => 'boolean',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->pdfPageNumber(1)
            ->nonQueued()
            ->fit(Fit::Contain, 300, 300);

        $this->addMediaConversion('preview')
            ->pdfPageNumber(1)
            ->nonQueued()
            ->fit(Fit::Contain, 1024, 1024);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection($this->getAssetCollection());
        $this->mergeTranslatableMediaCollection([$this->getAssetCollection()]);
    }

    public function getLocalizedAssetMedia(): ?Media
    {
        return $this->getFirstMedia(self::MEDIA_COLLECTION_ASSETS, ['locale' => app()->getLocale()])
            ?? $this->getFirstMedia(self::MEDIA_COLLECTION_ASSETS);
    }

    public function getAssetCollection(): string
    {
        return self::MEDIA_COLLECTION_ASSETS;
    }

    public function getViewUrl(?string $locale = null): string
    {
        // add current locale if not passed, the asset controller will use the default locale if none is passed.
        if (! $locale && FilamentFlexibleBlocksAssetManagerConfig::hasTranslatableAssets()) {
            $locale = app()->getLocale();
        }

        return route('filament-flexible-blocks-asset-manager.asset_index', [
            'assetId' => $this,
            'locale' => $locale,
        ]);
    }

    public function getDownloadFileName(): ?string
    {
        $media = $this->getLocalizedAssetMedia();

        if (! $media) {
            return null;
        }

        $extension = $media->extension;

        if (! $this->use_custom_file_name || ! $this->custom_file_name) {
            return $media->file_name;
        }

        return $this->buildSafeFileName($this->custom_file_name, $extension) ?? $media->file_name;
    }

    public static function buildSafeFileName(string $filename, string $extension): ?string
    {
        // Strip directory traversal, shell/path metacharacters and Unicode bidirectional
        // overrides (U+202A–U+202E, U+2066–U+2069, U+200E/F) which can spoof the displayed extension.
        $filename = str_replace('..', '', $filename);
        $filename = preg_replace('/[\/\\\\;&`<>\x00|\x{202A}-\x{202E}\x{2066}-\x{2069}\x{200E}\x{200F}]/u', '', $filename);

        $filename_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $name = pathinfo($filename, PATHINFO_FILENAME);

        if (trim($name) === '') {
            return null;
        }

        if ($filename_extension === $extension || $filename_extension === '') {
            return $name . '.' . $extension;
        }

        return $name . '.' . $filename_extension . '.' . $extension;
    }

    public function getPreviewUrl(?string $locale = null): string
    {
        return $this->getViewUrl($locale);
    }
}
