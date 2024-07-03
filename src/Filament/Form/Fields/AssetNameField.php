<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Form\Fields;

use Filament\Forms\Components\TextInput;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasTranslatableHint;

class AssetNameField extends TextInput
{
    use HasTranslatableHint;

    const FIELD = 'name';

    public static function create(bool $required = false): static
    {
        $field = self::FIELD;

        return static::make($field)
            ->label(trans("filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.form_component.{$field}_lbl"))
            ->maxLength(255)
            ->live()
            ->addsTranslatableHint()
            ->required($required);
    }

}

