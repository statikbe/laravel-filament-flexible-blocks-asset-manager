<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Actions\CopyUrlHeaderAction;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Actions\FlexibleLocaleSwitcher;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\EditRecord\Concerns\TranslatableWithMedia;

class EditAsset extends EditRecord
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
            CopyUrlHeaderAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
