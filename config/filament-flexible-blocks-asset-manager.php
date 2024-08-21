<?php

// config for Statik/FilamentFlexibleBlocksAssetManager

return [
    /*
    |--------------------------------------------------------------------------
    | Translatable assets
    |--------------------------------------------------------------------------
    |
    | Allows to translate the assets in different languages.
    */
    'translatable_assets' => true,

    /*
    |--------------------------------------------------------------------------
    | Navigation group
    |--------------------------------------------------------------------------
    |
    | Sets the navigation group label to which the assets table is added.
    */
    'navigation_group' => null,

    /*
    |--------------------------------------------------------------------------
    | Model & Resource
    |--------------------------------------------------------------------------
    |
    | You can override the model and resource that are used. Be sure to inherit from our defaults.
    */
    'model' => \Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset::class,
    'resource' => \Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Resources\AssetResource::class,

    /*
    |--------------------------------------------------------------------------
    | Image editor
    |--------------------------------------------------------------------------
    |
    | Enables the image editor. You can set preset aspect ratios, the editor mode of Cropper.js, and the default
    | viewport size.
    | see https://filamentphp.com/docs/3.x/forms/fields/file-upload#setting-the-image-editors-mode
    */
    'image_editor' => [
        'enabled' => false,
        'aspect_ratios' => [
            null,
            '16:9',
            '4:3',
            '1:1',
        ],
        'mode' => 1, // see https://github.com/fengyuanchen/cropperjs#viewmode
        'empty_fill_colour' => null,  // e.g. #000000
        'viewport' => [
            'width' => 1920,
            'height' => 1080,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Accepted file types
    |--------------------------------------------------------------------------
    |
    | Sets the file type validation, use an array of mime types.
    | see https://filamentphp.com/docs/3.x/forms/fields/file-upload#file-type-validation
    */
    'accepted_file_types' => [
        //'application/pdf'
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage disk
    |--------------------------------------------------------------------------
    |
    | The file system disk in which the assets will be stored.
    */
    'storage_disk' => null,

    /*
    |--------------------------------------------------------------------------
    | Storage directory
    |--------------------------------------------------------------------------
    |
    | The directory on the file system disk in which the assets will be stored.
    */
    'storage_directory' => null,

    /*
    |--------------------------------------------------------------------------
    | Storage visibility
    |--------------------------------------------------------------------------
    |
    | The visibility of the assets, see Spatie Medialibrary docs.
    */
    'storage_visibility' => 'public',

    /*
    |--------------------------------------------------------------------------
    | Asset authorisation
    |--------------------------------------------------------------------------
    |
    | The assets URLs can be protected and access to the Filament can be configured.
    | You need to define a new model policy and add an extra function for the public
    | file access of the asset. The name of this function needs to be added to the key
    | 'gate'. The policy class needs to be added to the key 'policy'.
    | The middleware is only used for assets URLs. You can get the assetId in the middleware by
    | calling $request->route('assetId')
    |
    | see https://laravel.com/docs/11.x/authorization#gates and
    | https://laravel.com/docs/11.x/authorization#writing-policies
    */
    'asset_authorisation' => [
        //'gate' => 'asset-access',
        //'policy' => AssetPolicy::class,
        //'middleware' => AssetRedirectMiddleware::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset route prefix
    |--------------------------------------------------------------------------
    |
    | You can change the start of the route path by setting this config.
    | Do not add a slash as the last character this will be added automatically.
    */
    'asset_route_prefix' => '/asset',
];
