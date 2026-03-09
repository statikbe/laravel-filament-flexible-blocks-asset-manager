<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Form\Fields;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasTranslatableHint;

class TranslatableTextInput extends TextInput
{
    use HasTranslatableHint;
}

class AssetCustomFileNameField extends Group
{
    const FIELD = 'custom_file_name';

    const TOGGLE = 'use_custom_file_name';

    public static function create(): static
    {
        $field = self::FIELD;

        return static::make([
            Toggle::make(self::TOGGLE)
                ->label(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.form_component.has_custom_file_name_lbl'))
                ->live(),

            TranslatableTextInput::make($field)
                ->label(trans("filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.form_component.{$field}_lbl"))
                ->maxLength(255)
                ->addsTranslatableHint()
                ->visible(fn ($get) => $get(self::TOGGLE)),
        ]);
    }
}
