@props([
    'as' => 'div',
    'class' => null,
])

<{{ $as }} 
    @contextmenu="show($event)"
    {{ $attributes->merge(['class' => cn('select-none', $class)]) }}
>
    {{ $slot }}
</{{ $as }}>