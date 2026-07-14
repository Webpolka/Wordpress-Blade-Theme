@props([
    'as'    => 'div',
    'class' => null,
])

<{{ $as }} 
    @click="open = !open"
    @keydown.enter="open = !open"
    :aria-expanded="open"
    role="button"
    tabindex="0"
    class="{{ cn('inline-flex items-center justify-center cursor-pointer outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background rounded-md', $class) }}"
>
    {{ $slot }}
</{{ $as }}>