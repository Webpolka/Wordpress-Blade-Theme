@props([
    'class' => null,
    'align' => 'start', // start | end | between
])

@php
    $alignClass = $align === 'end' 
        ? 'justify-end' 
        : ($align === 'between' ? 'justify-between' : 'justify-start');
@endphp

<div class="{{ cn('flex items-center gap-2 p-6 pt-0', $alignClass, $class) }}">
    {{ $slot }}
</div>