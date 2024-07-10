<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources;

use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Form\Fields\AssetMediaField;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Form\Fields\AssetNameField;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Pages\CreateAsset;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Pages\EditAsset;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Pages\ListAssets;
use Statikbe\FilamentFlexibleBlocksAssetManager\FilamentFlexibleBlocksAssetManagerConfig;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

class AssetResource extends Resource
{
    use Translatable;

    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function getNavigationLabel(): string
    {
        return trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.asset_nav_lbl');
    }

    public static function getLabel(): string
    {
        return trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.asset_lbl');
    }

    public static function getPluralLabel(): string
    {
        return trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.asset_plural_lbl');
    }

    public static function getNavigationGroup(): ?string
    {
        return FilamentFlexibleBlocksAssetManagerConfig::getNavigationGroup();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        AssetNameField::create(true),
                        AssetMediaField::create(FilamentFlexibleBlocksAssetManagerConfig::hasTranslatableAssets()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(trans('filament-flexible-blocks-asset-manager::filament-flexible-blocks-asset-manager.form_component.name_lbl'))
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    })
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                //ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssets::route('/'),
            'create' => CreateAsset::route('/create'),
            'edit' => EditAsset::route('/{record}/edit'),
        ];
    }
}
