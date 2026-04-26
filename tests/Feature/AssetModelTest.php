<?php

use Illuminate\Support\Facades\Storage;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

it('returns assets as the asset collection name', function () {
    $asset = new Asset;

    expect($asset->getAssetCollection())->toBe('assets');
});

it('can add media to the assets collection', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);

    expect($asset->getFirstMedia('assets'))->not->toBeNull();
});

it('registers thumbnail conversion', function () {
    $asset = new Asset;
    $asset->registerMediaConversions();

    $conversionNames = collect($asset->mediaConversions)->map(fn ($c) => $c->getName())->toArray();

    expect($conversionNames)->toContain('thumbnail');
});

it('has translatable name attribute', function () {
    $asset = Asset::create([
        'name' => ['en' => 'English Name', 'nl' => 'Nederlandse Naam'],
    ]);

    app()->setLocale('en');
    expect($asset->name)->toBe('English Name');

    app()->setLocale('nl');
    expect($asset->name)->toBe('Nederlandse Naam');
});

it('generates view URL without locale', function () {
    config()->set('filament-flexible-blocks-asset-manager.translatable_assets', false);
    $asset = Asset::create(['name' => 'Test Asset']);

    $url = $asset->getViewUrl();

    expect($url)->toContain('/asset/'.$asset->id);
});

it('generates view URL with locale', function () {
    $asset = Asset::create(['name' => 'Test Asset']);

    $url = $asset->getViewUrl('nl');

    expect($url)->toContain('/asset/'.$asset->id.'/nl');
});

it('getPreviewUrl equals getViewUrl', function () {
    config()->set('filament-flexible-blocks-asset-manager.translatable_assets', false);
    $asset = Asset::create(['name' => 'Test Asset']);

    expect($asset->getPreviewUrl())->toBe($asset->getViewUrl());
});

it('includes app locale in URL when translatable and no locale given', function () {
    setupTranslatableAssets();
    $asset = Asset::create(['name' => ['en' => 'EN', 'nl' => 'NL']]);

    app()->setLocale('nl');
    $url = $asset->getViewUrl();

    expect($url)->toContain('/asset/'.$asset->id.'/nl');
});

it('cleans up media when asset is deleted', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);
    $media = $asset->getFirstMedia('assets');
    $disk = $media->disk;
    $path = $media->getPathRelativeToRoot();

    $asset->delete();

    expect(Storage::disk($disk)->exists($path))->toBeFalse();
});
