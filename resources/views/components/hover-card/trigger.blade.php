@props([
    'as' => 'div',
    'class' => null,
])

<{{ $as }} 
    @mouseenter="show()" 
    @mouseleave="hide()"
    @focus="show()"
    @blur="hide()"
    {{ $attributes->merge(['class' => cn('inline-flex outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-800 rounded-md', $class)]) }}
>
    {{ $slot }}
</{{ $as }}>