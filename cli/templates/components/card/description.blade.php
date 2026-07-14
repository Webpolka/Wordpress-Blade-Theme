@props([
    'as'    => 'p',
    'class' => null,
])

<{{ $as }} class="{{ cn('text-sm text-gray-500 dark:text-gray-400', $class) }}">
    {{ $slot }}
</{{ $as }}>