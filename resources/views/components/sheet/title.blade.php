@props([
    'as' => 'h2',
    'class' => null,
])

<{{ $as }} class="{{ cn('text-lg font-semibold text-slate-900 dark:text-slate-100', $class) }}">
    {{ $slot }}
</{{ $as }}>