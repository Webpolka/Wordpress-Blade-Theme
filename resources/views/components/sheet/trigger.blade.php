@props([
    'as' => 'div',
    'class' => null,
])

<{{ $as }} 
    @click="open = true"
    @keydown.enter="open = true"
    role="button"
    tabindex="0"
    class="{{ cn('inline-flex cursor-pointer outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background rounded-md', $class) }}"
>
    {{ $slot }}
</{{ $as }}>