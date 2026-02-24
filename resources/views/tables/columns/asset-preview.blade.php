@php
    $record = $getRecord();
    $media = $record->getLocalizedAssetMedia();

    if (! $media || ! $media->hasGeneratedConversion('thumbnail')) {
        $thumbnailUrl = null;
    } else {
        $thumbnailUrl = $media->getUrl('thumbnail');
    }

    $previewUrl = $media?->hasGeneratedConversion('preview') ? $media->getUrl('preview') : $thumbnailUrl;
    $isImage = $media && str_starts_with($media->mime_type, 'image/');
@endphp

@if($thumbnailUrl)
    <div
        x-data
        x-on:mouseenter="$refs.panel.open($refs.trigger)"
        x-on:mouseleave="$refs.panel.close()"
    >
        <img
            x-ref="trigger"
            src="{{ $thumbnailUrl }}"
            style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; cursor: pointer; object-fit: {{ $isImage ? 'cover' : 'contain' }};"
        />

        <div
            x-ref="panel"
            x-cloak
            x-float.placement.left.offset.flip.shift.teleport="{ offset: 8 }"
            x-transition.opacity.duration.150ms
            class="fi-asset-preview-panel"
        >
            <img
                src="{{ $previewUrl }}"
                style="display: block; max-width: 24rem; max-height: 24rem; object-fit: contain;"
            />
        </div>
    </div>

    @once
        <style>
            .fi-asset-preview-panel {
                position: absolute;
                z-index: 50;
                border-radius: 0.5rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                padding: 0.5rem;
                background: white;
                pointer-events: none;
            }

            .dark .fi-asset-preview-panel {
                background: rgb(17 24 39); /* gray-900 */
            }
        </style>
    @endonce
@endif