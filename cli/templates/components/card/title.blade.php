@props([
    'as'    => 'h3',
    'class' => null,
])

<{{ $as }} class="{{ cn('font-semibold leading-none tracking-tight text-card-foreground', $class) }}">
    {{ $slot }}
</{{ $as }}>