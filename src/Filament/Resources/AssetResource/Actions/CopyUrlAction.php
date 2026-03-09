<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Actions\CopyAction;
use Filament\Support\Icons\Heroicon;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

class CopyUrlAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'copy_url';
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this
            ->label(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.action.copy_url'))
            ->icon(Heroicon::OutlinedClipboardDocument)
            ->color('gray')
            ->modalHeading(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.action.copy_url'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.action.close'))
            ->schema(function (): array {
                /** @var Asset $record */
                $record = $this->getRecord();

                return [
                    TextInput::make('url')
                        ->label(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.action.asset_url'))
                        ->default($record->getViewUrl())
                        ->readOnly()
                        ->extraInputAttributes([
                            'x-on:click' => '$el.select()',
                        ])
                        ->suffixAction(
                            CopyAction::make()
                                ->icon(Heroicon::OutlinedClipboard)
                                ->copyMessage(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.action.url_copied'))
                        ),
                ];
            });
    }
}
