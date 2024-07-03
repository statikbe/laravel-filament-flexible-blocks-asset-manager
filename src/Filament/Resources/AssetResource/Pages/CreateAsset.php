<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\CreateRecord\Concerns\TranslatableWithMedia;

class CreateAsset extends CreateRecord
{
    use TranslatableWithMedia;

    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
