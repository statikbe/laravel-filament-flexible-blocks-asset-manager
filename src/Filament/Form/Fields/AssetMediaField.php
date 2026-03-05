<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ImageField;

class AssetMediaField extends ImageField
{
    const FIELD = 'asset_media';

    public static function create(bool $translatable = false): SpatieMediaLibraryFileUpload
    {
        $field = self::FIELD;
        $component = static::createImageField($field, $translatable, FilamentFlexibleBlocksAssetManagerConfig::getImageEditor())
            ->label(trans("filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.form_component.{$field}_lbl"))
            ->disk(FilamentFlexibleBlocksAssetManagerConfig::getStorageDisk())
            ->directory(FilamentFlexibleBlocksAssetManagerConfig::getStorageDirectory())
            ->visibility(FilamentFlexibleBlocksAssetManagerConfig::getStorageVisibility())
            ->collection(function (Model $record) {
                /** @var Asset $record */
                return $record->getAssetCollection();
            })
            ->conversion('thumbnail')
            ->getUploadedFileUsing(static function (SpatieMediaLibraryFileUpload $component, string $file): ?array {
                if (! $component->getRecord()) {
                    return null;
                }

                /** @var ?Media $media */
                $media = $component->getRecord()->getRelationValue('media')->firstWhere('uuid', $file);

                /** @var ?Asset $record */
                $record = $component->getRecord();
                $downloadFileName = $record?->getDownloadFileName();

                $url = null;

                if ($component->getVisibility() === 'private') {
                    $conversion = $component->getConversion();

                    try {
                        $url = $media?->getTemporaryUrl(
                            now()->addMinutes(30)->endOfHour(),
                            (filled($conversion) && $media->hasGeneratedConversion($conversion)) ? $conversion : '',
                        );
                    } catch (\Throwable $exception) {
                        // This driver does not support creating temporary URLs.
                    }
                }

                if ($component->getConversion() && $media?->hasGeneratedConversion($component->getConversion())) {
                    $url ??= $media->getUrl($component->getConversion());
                }

                $url ??= $media?->getUrl();

                return [
                    'name' => $downloadFileName ?? $media?->getAttributeValue('name') ?? $media?->getAttributeValue('file_name'),
                    'size' => $media?->getAttributeValue('size'),
                    'type' => $media?->getAttributeValue('mime_type'),
                    'url' => $url,
                ];
            });

        $acceptedFileTypes = FilamentFlexibleBlocksAssetManagerConfig::getAcceptedFileTypes();
        if (! empty($acceptedFileTypes)) {
            $component->acceptedFileTypes($acceptedFileTypes);
        }

        return $component;
    }
}
