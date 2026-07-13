@props([
    'class' => null,
])

<div class="{{ cn('flex items-center gap-2 p-4 border-t border-slate-200 dark:border-slate-700 mt-auto', $class) }}">
    {{ $slot }}
</div>