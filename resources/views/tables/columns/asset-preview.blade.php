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
            class="w-10 h-10 rounded-lg {{ $isImage ? 'object-cover' : 'object-contain' }} cursor-pointer"
        />

        <div
            x-ref="panel"
            x-cloak
            x-float.placement.left.offset.flip.shift.teleport="{ offset: 8 }"
            x-transition.opacity.duration.150ms
            class="absolute z-50 rounded-lg shadow-2xl p-2 bg-white dark:bg-gray-900 pointer-events-none"
        >
            <img
                src="{{ $previewUrl }}"
                class="block max-w-96 max-h-96 object-contain"
            />
        </div>
    </div>
@endif