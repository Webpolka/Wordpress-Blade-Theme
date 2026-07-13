@props([
    'class' => null,
])

<div class="swiper-slide {{ cn($class, "h-auto") }}">
    {{ $slot }}
</div>