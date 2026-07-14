@props([
    'placement' => 'bottom-start', // bottom-start | bottom-end
    'width'     => 'w-80',
    'class'     => null,
])

@php
    $posClass = $placement === 'bottom-end' ? 'right-0' : 'left-0';
    
    // pt-2 вместо mt-2 создает невидимый "мост", чтобы мышка не теряла фокус при переходе!
    $wrapperClasses = cn('absolute z-50 top-full pt-2', $posClass, $width);
    
    // Design System: Семантические классы для плашки
    $innerClasses = cn(
        'rounded-xl border border-border bg-popover text-popover-foreground shadow-lg overflow-hidden',
        $class
    );
@endphp

<div 
    x-show="open"
    x-cloak
    @mouseenter="show()" 
    @mouseleave="hide()"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-1"
    class="{{ $wrapperClasses }}"
>
    <div class="{{ $innerClasses }}">
        {{ $slot }}
    </div>
</div>