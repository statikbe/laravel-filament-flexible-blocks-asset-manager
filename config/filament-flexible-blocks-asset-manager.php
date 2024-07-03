<?php

// config for Statik/FilamentFlexibleBlocksAssetManager
return [
    'translatable_assets' => true,

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

    'storage_disk' => null,

    'storage_directory' => null,

    'storage_visibility' => 'public',

];
