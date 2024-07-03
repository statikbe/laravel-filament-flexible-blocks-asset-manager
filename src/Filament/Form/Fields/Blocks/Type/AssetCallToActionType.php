<?php
namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Form\Fields\Blocks\Type;

use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type\CallToActionType;

class AssetCallToActionType extends CallToActionType
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->titleColumnName('name');
        $this->model(Asset::class);
        $this->label(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.asset_lbl'));

        $this->searchColumns(['name']);
        $this->getOptionLabelFromRecordUsing(function(Asset $record, ?string $locale){
            return $record->translate('name', $locale);
        });
    }
}
