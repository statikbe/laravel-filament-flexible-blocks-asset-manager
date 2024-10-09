<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
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
            ->conversion('thumbnail');

        $acceptedFileTypes = FilamentFlexibleBlocksAssetManagerConfig::getAcceptedFileTypes();
        if (! empty($acceptedFileTypes)) {
            $component->acceptedFileTypes($acceptedFileTypes);
        }

        return $component;
    }
}
