@props([
    'class' => null,
])

<div 
    role="tablist" 
    class="flex flex-wrap gap-x-4 gap-y-2 border-b border-gray-200 dark:border-gray-700 {{ $class }}"
>
    {{ $slot }}
</div>