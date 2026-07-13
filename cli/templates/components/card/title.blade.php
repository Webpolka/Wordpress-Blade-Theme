@props([
    'as'    => 'h3',
    'class' => null,
])

<{{ $as }} class="{{ cn('font-semibold leading-none tracking-tight text-gray-900 dark:text-white', $class) }}">
    {{ $slot }}
</{{ $as }}>