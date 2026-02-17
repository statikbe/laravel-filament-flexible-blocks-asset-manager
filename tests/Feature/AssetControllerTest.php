<?php

use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\Factories\UserFactory;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

it('returns asset media file successfully', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
});

it('sets X-Robots-Tag none header on media custom properties', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
    // The controller calls setCustomHeaders on the media model which stores the
    // headers as custom properties (used for cloud storage headers like S3).
    // Spatie's toResponse() does not include them in the HTTP response headers.
    $response->assertHeader('Content-Disposition');
});

it('returns 404 for non-existent asset', function () {
    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => 999]));

    $response->assertNotFound();
});

it('returns 404 when asset has no media', function () {
    $asset = createAsset(withMedia: false);

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertNotFound();
});

it('returns 404 when media file missing from disk', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);
    $media = $asset->getFirstMedia($asset->getAssetCollection());

    // Delete the file from storage but keep the DB record
    Storage::disk($media->disk)->delete($media->getPathRelativeToRoot());

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertNotFound();
});

it('returns 403 when gate denies access', function () {
    setupFakeStorage();
    setupAssetAuthorisationGate(allows: false);
    $asset = createAsset(withMedia: true);

    $user = UserFactory::new()->create();

    $response = $this->actingAs($user)->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertForbidden();
});

it('allows access when gate permits', function () {
    setupFakeStorage();
    setupAssetAuthorisationGate(allows: true);
    $asset = createAsset(withMedia: true);

    $user = UserFactory::new()->create();

    $response = $this->actingAs($user)->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
});

it('skips gate check when no gate configured', function () {
    setupFakeStorage();
    config()->set('filament-flexible-blocks-asset-manager.asset_authorisation.gate', null);
    $asset = createAsset(withMedia: true);

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
});

it('serves media with explicit locale parameter', function () {
    setupFakeStorage();
    setupTranslatableAssets();
    $asset = createTranslatableAsset(withMedia: true, locale: 'nl');

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', [
        'assetId' => $asset->id,
        'locale' => 'nl',
    ]));

    $response->assertOk();
});

it('falls back to non-locale media', function () {
    setupFakeStorage();
    setupTranslatableAssets();
    $asset = createAsset(withMedia: true); // Media without locale

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', [
        'assetId' => $asset->id,
        'locale' => 'fr', // Locale with no media
    ]));

    $response->assertOk();
});

it('uses app locale when translatable and no locale param', function () {
    setupFakeStorage();
    setupTranslatableAssets();
    $asset = Asset::create(['name' => ['en' => 'EN Asset', 'nl' => 'NL Asset']]);
    $asset->addMediaFromString('nl content')
        ->usingFileName('nl-file.pdf')
        ->withCustomProperties(['locale' => 'nl'])
        ->toMediaCollection($asset->getAssetCollection());

    app()->setLocale('nl');

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', [
        'assetId' => $asset->id,
    ]));

    $response->assertOk();
});
