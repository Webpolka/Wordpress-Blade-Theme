@props([
    'class' => null,
])

<div class="{{ cn('p-6 pt-0', $class) }}">
    {{ $slot }}
</div>