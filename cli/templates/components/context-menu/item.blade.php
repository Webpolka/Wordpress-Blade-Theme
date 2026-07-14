@props([
    'as' => 'button',
    'variant' => 'default', // default | destructive
    'class' => null,
])

@php
    // Design System: Семантические классы для пунктов меню
    $classes = cn(
        'flex items-center gap-2 w-full px-3 py-1.5 text-sm rounded-md transition-colors text-left',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background',
        $variant === 'destructive' 
            ? 'text-destructive hover:bg-destructive/10' 
            : 'text-foreground hover:bg-accent hover:text-accent-foreground',
        $class
    );
@endphp

<{{ $as }} 
    {{-- Добавляем type только если это кнопка --}}
    @if($as === 'button') type="button" @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</{{ $as }}>