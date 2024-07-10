<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasTranslatedMediaTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasTranslatableMedia;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable;

/**
 * @property string $name
 */
class Asset extends Model implements HasMedia, HasTranslatableMedia, Linkable
{
    use HasTranslatedMediaTrait;
    use HasTranslations;
    use InteractsWithMedia;

    const MEDIA_COLLECTION_ASSETS = 'assets';

    public $translatable = ['name'];

    public $guarded = [];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
        //TODO configurable conversions.
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection($this->getAssetCollection());
        $this->mergeTranslatableMediaCollection([$this->getAssetCollection()]);
    }

    public function getAssetCollection(): string
    {
        return self::MEDIA_COLLECTION_ASSETS;
    }

    public function getViewUrl(?string $locale = null): string
    {
        return route('filament-flexible-blocks-asset-manager.asset_index', [
            'asset' => $this,
            'locale' => $locale,
        ]);
    }

    public function getPreviewUrl(?string $locale = null): string
    {
        $this->getViewUrl($locale);
    }
}
