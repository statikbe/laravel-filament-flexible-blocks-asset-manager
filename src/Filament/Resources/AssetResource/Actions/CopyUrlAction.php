<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Actions;

use Filament\Tables\Actions\Action;
use Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource\Actions\Concerns\HasCopyUrlAction;

class CopyUrlAction extends Action
{
    use HasCopyUrlAction;

    public static function getDefaultName(): ?string
    {
        return 'copy_url';
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpCopyUrl();
    }
}
