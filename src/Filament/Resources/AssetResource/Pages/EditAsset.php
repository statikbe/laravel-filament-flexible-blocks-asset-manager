<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\EditRecord\Concerns\TranslatableWithMedia;

class EditAsset extends EditRecord
{
    use TranslatableWithMedia;

    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
