@props([
    'class' => null,
])

<div class="swiper-slide h-auto {{ $class }}">
    {{ $slot }}
</div>