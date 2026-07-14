@props([
    'class' => null,
])

<div class="{{ cn('flex items-center gap-2 p-4 border-t border-border mt-auto', $class) }}">
    {{ $slot }}
</div>