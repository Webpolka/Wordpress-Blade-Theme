@props([
    'class' => null,
])

<div 
    role="tablist" 
    class="{{ cn('flex flex-wrap gap-x-4 gap-y-2 border-b border-border', $class) }}"
>
    {{ $slot }}
</div>