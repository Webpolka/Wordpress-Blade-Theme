@props([
    'as'    => 'p',
    'class' => null,
])

<{{ $as }} class="{{ cn('text-sm text-muted-foreground', $class) }}">
    {{ $slot }}
</{{ $as }}>