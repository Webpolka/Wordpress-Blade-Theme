@props([
    'as' => 'div',
    'class' => null,
])

<{{ $as }} 
    @mouseenter="show()" 
    @mouseleave="hide()"
    @focusin="show()"
    @focusout="hide()"
    {{ $attributes->merge(['class' => cn('inline-flex outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background rounded-md', $class)]) }}
>
    {{ $slot }}
</{{ $as }}>