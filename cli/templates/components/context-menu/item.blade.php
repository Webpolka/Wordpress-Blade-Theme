@props([
    'as' => 'button',
    'variant' => 'default', // default | destructive
    'class' => null,
])

@php
    $classes = cn(
        'flex items-center gap-2 w-full px-3 py-1.5 text-sm rounded-md transition-colors text-left',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-800', // НОВОЕ: Фокус для клавиатуры
        $variant === 'destructive' 
            ? 'text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20' 
            : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700', // НОВОЕ: gray заменен на slate
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