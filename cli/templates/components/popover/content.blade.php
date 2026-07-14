@props([
    'placement' => 'bottom-start', // bottom-start | bottom-end | top-start | top-end | left | right
    'width'     => 'w-64',         // auto, w-64, w-80, w-96...
    'class'     => null,
])

@php
    $placements = [
        'bottom-start' => 'top-full mt-2 left-0',
        'bottom-end'   => 'top-full mt-2 right-0',
        'top-start'    => 'bottom-full mb-2 left-0',
        'top-end'      => 'bottom-full mb-2 right-0',
        'left'         => 'right-full mr-2 top-1/2 -translate-y-1/2',
        'right'        => 'left-full ml-2 top-1/2 -translate-y-1/2',
    ];

    $posClass = $placements[$placement] ?? $placements['bottom-start'];

    // Design System: Семантические классы для плашки
    $panelClasses = cn(
        'absolute z-50 rounded-xl border border-border bg-popover text-popover-foreground shadow-lg',
        $width,
        $posClass,
        $class
    );
@endphp

<div 
    x-show="open"    
    x-cloak
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="{{ $panelClasses }}"
>
    {{ $slot }}
</div>