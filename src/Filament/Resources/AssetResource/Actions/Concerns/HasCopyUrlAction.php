<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Actions\Concerns;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

trait HasCopyUrlAction
{
    protected function setUpCopyUrl(): void
    {
        $this
            ->label(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.action.copy_url'))
            ->icon(Heroicon::OutlinedClipboardDocument)
            ->color('gray')
            ->modalHeading(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.action.copy_url'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.action.close'))
            ->form(function (): array {
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
                            Action::make('copy')
                                ->icon(Heroicon::OutlinedClipboard)
                                ->label(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.action.copy'))
                                ->alpineClickHandler('
                                    const input = $el.closest(".fi-input-wrp").querySelector("input");
                                    navigator.clipboard.writeText(input.value);
                                    $tooltip("' . trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.action.url_copied') . '");
                                ')
                        ),
                ];
            });
    }
}
