<?php

use Livewire\Livewire;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Pages\CreateAsset;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Pages\EditAsset;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Pages\ListAssets;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

it('uses the correct model', function () {
    expect(AssetResource::getModel())->toBe(Asset::class);
});

it('has index page', function () {
    $pages = AssetResource::getPages();

    expect($pages)->toHaveKey('index');
});

it('has create page', function () {
    $pages = AssetResource::getPages();

    expect($pages)->toHaveKey('create');
});

it('has edit page', function () {
    $pages = AssetResource::getPages();

    expect($pages)->toHaveKey('edit');
});

it('renders the list page', function () {
    setupFilamentPanel();

    Livewire::test(ListAssets::class)
        ->assertSuccessful();
});

it('shows assets in the table', function () {
    setupFilamentPanel();

    $asset = Asset::create(['name' => 'My Test Asset']);

    Livewire::test(ListAssets::class)
        ->assertSee('My Test Asset');
});

it('renders the create page', function () {
    setupFilamentPanel();

    Livewire::test(CreateAsset::class)
        ->assertSuccessful();
});

it('renders the edit page', function () {
    setupFilamentPanel();

    $asset = Asset::create(['name' => 'Test Asset']);

    Livewire::test(EditAsset::class, ['record' => $asset->id])
        ->assertSuccessful();
});
