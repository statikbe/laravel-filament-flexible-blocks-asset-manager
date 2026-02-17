<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\Factories\UserFactory;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;
use Statikbe\FilamentFlexibleBlocksAssetManager\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/*
|--------------------------------------------------------------------------
| Test Helpers
|--------------------------------------------------------------------------
*/

/**
 * Set up fake storage for media uploads.
 */
function setupFakeStorage(): void
{
    Storage::fake('public');
}

/**
 * Set up Filament panel and authenticate a test user.
 */
function setupFilamentPanel(): void
{
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $user = UserFactory::new()->create();
    test()->actingAs($user);
}

/**
 * Create an asset, optionally with media attached.
 */
function createAsset(bool $withMedia = false, ?string $locale = null): Asset
{
    $asset = Asset::create([
        'name' => $locale ? ['en' => 'Test Asset EN', 'nl' => 'Test Asset NL'] : 'Test Asset',
    ]);

    if ($withMedia) {
        $customProperties = $locale ? ['locale' => $locale] : [];
        $asset->addMediaFromString('test file content')
            ->usingFileName('test-file.pdf')
            ->withCustomProperties($customProperties)
            ->toMediaCollection($asset->getAssetCollection());
    }

    return $asset->fresh();
}

/**
 * Create an asset with translatable config enabled.
 */
function createTranslatableAsset(bool $withMedia = false, ?string $locale = null): Asset
{
    config()->set('filament-flexible-blocks-asset-manager.translatable_assets', true);

    return createAsset(withMedia: $withMedia, locale: $locale ?? 'en');
}

/**
 * Enable translatable assets in config.
 */
function setupTranslatableAssets(): void
{
    config()->set('filament-flexible-blocks-asset-manager.translatable_assets', true);
}

/**
 * Configure an asset authorisation gate.
 */
function setupAssetAuthorisationGate(bool $allows = true): void
{
    config()->set('filament-flexible-blocks-asset-manager.asset_authorisation.gate', 'asset-access');
    Gate::define('asset-access', fn () => $allows);
}
