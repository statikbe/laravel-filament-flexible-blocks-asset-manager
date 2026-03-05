<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Actions;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

class DownloadAssetAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'download_asset';
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this
            ->label(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.action.download'))
            ->icon(Heroicon::ArrowDownTray)
            ->hidden(fn ($record) => ! $record->getLocalizedAssetMedia())
            ->action(function ($record) {
                $filename = $record->getDownloadFileName();
                $path = $record->getLocalizedAssetMedia()->getPath();

                return response()->download($path, $filename);
            });
    }
}