<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Actions\FlexibleLocaleSwitcher;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\CreateRecord\Concerns\TranslatableWithMedia;

class CreateAsset extends CreateRecord
{
    use TranslatableWithMedia;

    protected static string $resource = AssetResource::class;

    public static function getResource(): string
    {
        return FilamentFlexibleBlocksAssetManagerConfig::getResource();
    }

    protected function getHeaderActions(): array
    {
        return [
            FlexibleLocaleSwitcher::make(),
        ];
    }
}
