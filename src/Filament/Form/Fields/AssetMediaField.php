<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ImageField;
use Filament\Support\Assets\Asset;

class AssetMediaField extends ImageField
{
    const FIELD = 'asset_media';

    public static function create(bool $translatable = false): SpatieMediaLibraryFileUpload
    {
        $field = self::FIELD;
        $component = static::createImageField($field, $translatable, config('filament-flexible-blocks-asset-manager.image_editor', null))
            ->label(trans("filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.form_component.{$field}_lbl"))
            ->disk(config('filament-flexible-blocks-asset-manager.storage_disk'))
            ->directory(config('filament-flexible-blocks-asset-manager.storage_directory'))
            ->visibility(config('filament-flexible-blocks-asset-manager.storage_visibility') ?? 'public')
            ->collection(function (Model $record) {
                /** @var Asset $record */
                return $record->getAssetCollection();
            })
            ->conversion('thumbnail');

        $acceptedFileTypes = config('filament-flexible-blocks-asset-manager.accepted_file_types') ?? [];
        if(!empty($acceptedFileTypes)){
            $component->acceptedFileTypes($acceptedFileTypes);
        }

        return $component;
    }
}
