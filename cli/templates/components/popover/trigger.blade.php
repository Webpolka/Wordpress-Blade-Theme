@props([
    'as'    => 'div', // Изменили по умолчанию на div, чтобы не ломать HTML при вложении кнопок
    'class' => null,
])

<{{ $as }} 
    @click="open = !open"
    @keydown.enter="open = !open"
    :aria-expanded="open"
    role="button"
    tabindex="0"
    class="inline-flex items-center justify-center cursor-pointer outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded-md {{ $class }}"
>
    {{ $slot }}
</{{ $as }}>