<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Form\Fields;

use Filament\Forms\Components\TextInput;

class AssetCustomFileNameField extends TextInput
{
    const FIELD = 'custom_file_name';

    public static function create(): static
    {
        $field = self::FIELD;

        return static::make($field)
            ->label(trans("filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.form_component.{$field}_lbl"))
            ->maxLength(255);
    }
}
