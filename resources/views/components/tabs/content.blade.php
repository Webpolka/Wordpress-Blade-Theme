@props([
    'value' => null,
    'class' => null,
])

@php
    $tabId = 'tab-' . Illuminate\Support\Str::slug($value);
    $panelId = 'panel-' . Illuminate\Support\Str::slug($value);
@endphp

<div
    x-show="activeTab === '{{ $value }}'"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0"
    
    role="tabpanel"
    id="{{ $panelId }}"
    aria-labelledby="{{ $tabId }}"
    
    x-cloak
    class="{{ cn('mt-4 focus:outline-none', $class) }}"
>
    {{ $slot }}
</div>