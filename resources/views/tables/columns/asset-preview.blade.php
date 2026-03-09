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
        style="padding-left: 3rem;"
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
            x-float.placement.left.offset.flip.shift.teleport="{ offset: 8, shift: { crossAxis: true, padding: 8 } }"
            x-transition.opacity.duration.150ms
            style="position: absolute; z-index: 50; pointer-events: none;"
        >
            <img
                src="{{ $previewUrl }}"
                style="display: block; max-width: 24rem; max-height: 24rem; object-fit: contain; border-radius: 0.5rem;"
            />
        </div>
    </div>
@else
    <div style="position: relative; width: 2.5rem; height: 2.5rem; display: flex; align-items: center; justify-content: center; margin-left: 3rem;">
        <x-heroicon-o-document class="text-gray-400 dark:text-gray-500" style="width: 2.5rem; height: 2.5rem;" />
        @if($media?->extension)
            <span class="text-gray-400 dark:text-gray-500" style="position: absolute; font-size: 0.4rem; font-weight: bold; text-transform: uppercase; margin-top: 0.25rem;">
                {{ $media->extension }}
            </span>
        @endif
    </div>
@endif
