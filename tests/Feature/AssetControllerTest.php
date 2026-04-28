<?php

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\Factories\UserFactory;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

it('returns asset media file successfully', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
});

it('sets X-Robots-Tag none header on the response', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
    $response->assertHeader('X-Robots-Tag', 'none');
    $response->assertHeader('Content-Disposition');
});

it('streams the file content in the response body', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
    expect($response->streamedContent())->toBe('test file content');
});

it('sets Content-Type from the media mime type', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);
    $media = $asset->getFirstMedia($asset->getAssetCollection());

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
    expect($response->headers->get('Content-Type'))->toContain($media->mime_type);
});

it('sets Content-Disposition inline with the filename', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
    expect($response->headers->get('Content-Disposition'))
        ->toContain('inline')
        ->toContain('test-file.pdf');
});

it('sets Content-Length matching the media size', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);
    $media = $asset->getFirstMedia($asset->getAssetCollection());

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
    $response->assertHeader('Content-Length', (string) $media->size);
});

// Status-level guard test: verifies the 404 short-circuit when readStream() returns
// null despite exists() being true. Does not exercise the streaming closure body —
// the streamed-content test above covers the happy path.
it('returns 404 when readStream returns null despite the file existing', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);
    $media = $asset->getFirstMedia($asset->getAssetCollection());

    Storage::shouldReceive('disk')
        ->with($media->disk)
        ->andReturn(Mockery::mock(FilesystemAdapter::class, function ($mock) use ($media) {
            $mock->shouldReceive('exists')
                ->with($media->getPathRelativeToRoot())
                ->andReturn(true);
            $mock->shouldReceive('readStream')
                ->with($media->getPathRelativeToRoot())
                ->andReturn(null);
        }));

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertNotFound();
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

it('sets X-Content-Type-Options nosniff to block mime sniffing', function () {
    setupFakeStorage();
    $asset = createAsset(withMedia: true);

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
});

it('forces attachment for HTML to prevent stored XSS', function () {
    setupFakeStorage();
    $asset = Asset::create(['name' => 'XSS Asset']);
    $asset->addMediaFromString('<script>alert(1)</script>')
        ->usingFileName('payload.html')
        ->toMediaCollection($asset->getAssetCollection());

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
    expect($response->headers->get('Content-Disposition'))->toStartWith('attachment;');
});

it('forces attachment for SVG to prevent stored XSS', function () {
    setupFakeStorage();
    $asset = Asset::create(['name' => 'SVG Asset']);
    $asset->addMediaFromString('<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>')
        ->usingFileName('payload.svg')
        ->toMediaCollection($asset->getAssetCollection());

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
    expect($response->headers->get('Content-Disposition'))->toStartWith('attachment;');
});

it('strips Unicode bidi overrides from the Content-Disposition filename', function () {
    setupFakeStorage();
    $asset = Asset::create(['name' => 'Bidi Asset']);
    // Filename contains U+202E (RTLO) which a browser would otherwise render
    // so "fdp.exe" displays as "exe.pdf" — classic extension-spoofing trick.
    $asset->addMediaFromString('content')
        ->usingFileName("invoice\u{202E}fdp.pdf")
        ->toMediaCollection($asset->getAssetCollection());

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
    $disposition = $response->headers->get('Content-Disposition');
    expect($disposition)
        ->not->toContain("\u{202E}")
        ->not->toContain('%E2%80%AE')
        ->toContain('invoicefdp.pdf');
});

it('still serves the file when the filename reduces to nothing after sanitization', function () {
    setupFakeStorage();
    $asset = Asset::create(['name' => 'Empty After Sanitize']);
    // Filename made entirely of bidi overrides plus a real extension.
    $asset->addMediaFromString('content')
        ->usingFileName("\u{202E}\u{202D}.pdf")
        ->toMediaCollection($asset->getAssetCollection());

    $response = $this->get(route('filament-flexible-blocks-asset-manager.asset_index', ['assetId' => $asset->id]));

    $response->assertOk();
    $response->assertHeader('Content-Disposition');
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
