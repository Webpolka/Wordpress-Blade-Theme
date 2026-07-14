@props([
    'as' => 'h2',
    'class' => null,
])

<{{ $as }} class="{{ cn('text-lg font-semibold text-card-foreground', $class) }}">
    {{ $slot }}
</{{ $as }}>