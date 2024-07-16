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
    | Asset Filament authorisation
    |--------------------------------------------------------------------------
    |
    | To authorise access to the Filament resource, you can configure a policy,
    | see https://laravel.com/docs/11.x/authorization#writing-policies
    */
    'asset_filament_authorisation' => null, //AssetFilamentPolicy::class,

    /*
    |--------------------------------------------------------------------------
    | Asset authorisation
    |--------------------------------------------------------------------------
    |
    | The assets URLs can be protected. This can be done in different ways:
    | 1. a gate, see https://laravel.com/docs/11.x/authorization#gates
    |    You can define a gate that takes the asset record as argument.
    | 2. a policy, see https://laravel.com/docs/11.x/authorization#writing-policies
    |    You can write a policy for the Asset model. The `view` policy will be used to authorise.
    */
    'asset_authorisation' => [
        //'gate' => 'asset-access',
        //'policy' => AssetPolicy::class,
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
