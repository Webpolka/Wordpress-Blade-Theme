@props([
    'class' => null,
])

<div class="{{ cn('flex flex-col space-y-1.5 p-6', $class) }}">
    {{ $slot }}
</div>