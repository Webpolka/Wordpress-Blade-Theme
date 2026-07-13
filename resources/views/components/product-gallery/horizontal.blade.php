{{--
  ============================================================
  Компонент: Product Gallery Horizontal (WooCommerce)
  ============================================================

  ------------------------------------------------------------
  1. ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - images        (array)  – Массив картинок и видео.
    - class         (string) – Доп. классы для обертки.
    - mainArrows    (bool)   – Показывать стрелки на главном слайдере. По умолчанию: true.
    - thumbArrows   (bool)   – Показывать стрелки на слайдере тумбнейлов. По умолчанию: true.
    - parallax      (bool)   – Включить эффект параллакса. По умолчанию: true.
    - lightBoxZoom  (bool)   – Опция зум в лайтбоксе при двойном клике.
    - aspect        (string) – Tailwind класс для главного слайдера (apsect-square, aspect-[2/1]).
    - mainSettings  (array)  – Доп. настройки Swiper для главного слайдера (merge с дефолтными).
    - thumbSettings (array)  – Доп. настройки Swiper для слайдера тумбнейлов (merge с дефолтными).

  ------------------------------------------------------------
  3. ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ------------------------------------------------------------
  // Передача кастомных настроек (например, убрать пагинацию или изменить скорость)
  <x-product-gallery.horizontal 
      :images="$gallery_media" 
      :main-settings="['loop' => true, 'speed' => 800]"
      :thumb-settings="['slidesPerView' => 3]"
  />
--}}

@props([
    'images' => [],
    'class' => null,
    'mainArrows' => true,
    'thumbArrows' => true,
    'parallax' => true,
    'lightBoxZoom' => true,
    'aspect' => 'aspect-square',
    'mainSettings' => [],
    'thumbSettings' => [],
])

@php
    $parsedItems = array_map(function ($item) {
        $src = is_array($item) ? $item['src'] ?? '' : $item;
        $thumb = is_array($item) ? $item['thumb'] ?? '' : '';
        $title = is_array($item) ? $item['title'] ?? '' : '';
        $type = 'image';

        // Красивый SVG плейсхолдер (серый фон + иконка Play)
        $placeholder = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='600' viewBox='0 0 800 600'%3E%3Crect width='800' height='600' fill='%23f0f4f8'/%3E%3Crect x='0' y='0' width='200' height='150' fill='%23f87171'/%3E%3Crect x='200' y='0' width='200' height='150' fill='%23fbbf24'/%3E%3Crect x='400' y='0' width='200' height='150' fill='%2334d399'/%3E%3Crect x='600' y='0' width='200' height='150' fill='%2360a5fa'/%3E%3Crect x='0' y='150' width='400' height='300' fill='%23e5e7eb'/%3E%3Crect x='400' y='150' width='400' height='300' fill='%23d1d5db'/%3E%3Crect x='0' y='450' width='800' height='150' fill='%239ca3af'/%3E%3Ctext x='400' y='320' font-family='Arial' font-size='48' fill='%234b5563' text-anchor='middle' font-weight='bold'%3EPLACEHOLDER%3C/text%3E%3Ctext x='400' y='370' font-family='Arial' font-size='20' fill='%236b7280' text-anchor='middle'%3E800 x 600%3C/text%3E%3C/svg%3E";

        // Автоматическое получение превью для видео
        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $src, $m) || preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $src, $m)) {
            $type = 'video';
            $videoId = $m[1];
            $src = 'https://www.youtube.com/embed/' . $videoId . '?autoplay=1&mute=1&rel=0';
            if (empty($thumb)) $thumb = 'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg';
        } elseif (preg_match('/vimeo\.com\/([0-9]+)/', $src, $m)) {
            $type = 'video';
            $videoId = $m[1];
            $src = 'https://player.vimeo.com/video/' . $videoId . '?autoplay=1&muted=1';
            if (empty($thumb)) $thumb = 'https://vumbnail.com/' . $videoId . '.jpg';
        } elseif (preg_match('/rutube\.ru\/(?:video|play\/embed|shorts)\/([a-zA-Z0-9_-]+)/', $src, $m)) {
            $type = 'video';
            $src = 'https://rutube.ru/play/embed/' . $m[1] . '?autoplay=1';
            // НОВОЕ: Не пытаемся угадать превью Rutube, сразу ставим плейсхолдер
            if (empty($thumb)) $thumb = $placeholder;
        } elseif (preg_match('/vk\.com\/video(-?[0-9]+)_([0-9]+)/', $src, $m)) {
            $type = 'video';
            $src = 'https://vk.com/video_ext.php?oid=' . $m[1] . '&id=' . $m[2] . '&hd=2&autoplay=1';
            if (empty($thumb)) $thumb = $placeholder;
        } elseif (preg_match('/dailymotion\.com\/video\/([a-zA-Z0-9]+)/', $src, $m)) {
            $type = 'video';
            $videoId = $m[1];
            $src = 'https://www.dailymotion.com/embed/video/' . $videoId . '?autoplay=1';
            if (empty($thumb)) $thumb = 'https://www.dailymotion.com/thumbnail/video/' . $videoId;
        } elseif (preg_match('/\.(mp4|webm|ogg)$/', $src)) {
            $type = 'video';
            if (empty($thumb)) $thumb = $placeholder;
        }

        // Если это картинка и нет thumb - берем основной src
        if ($type === 'image' && empty($thumb)) {
            $thumb = $src;
        }

        return ['type' => $type, 'src' => $src, 'thumb' => $thumb, 'title' => $title];
    }, $images);
@endphp

<div class="block">

    <style>
        .product-gallery-horizontal-thumbs .swiper-slide:not(.swiper-slide-thumb-active) img:hover {
            border-color: orange;
        }
        .product-gallery-horizontal-thumbs .swiper-slide-thumb-active img {
            border-color: #3b82f6;
        }
    </style>

    <x-lightbox :items="$parsedItems" :zoom="$lightBoxZoom">    
        <div x-data="productGalleryHorizontal(@js(['main' => $mainSettings, 'thumbs' => $thumbSettings]))" class="{{ $class }}">

            {{-- ГЛАВНЫЙ СЛАЙДЕР --}}
            <div class="relative mb-1.5">                
                <div x-ref="mainRef" style="opacity: 0;"
                    class="{{ $aspect }} swiper h-[inherit] absolute inset-0 overflow-hidden transition-opacity duration-300 rounded-md"
                    @if($parallax) @mousemove="moveParallax($event)" @mouseleave="resetParallax()" @endif>
                    
                    <div class="swiper-wrapper">
                        @foreach ($parsedItems as $index => $item)
                            <div class="swiper-slide overflow-hidden">
                                <x-lightbox.trigger :index="$index" :play="$item['type'] === 'video'" class="block w-full h-full">
                                    @if ($item['type'] === 'image')
                                        <img src="{{ $item['src'] }}" alt="{{ $item['title'] ?? '' }}" draggable="false"
                                            class="w-full h-full object-cover cursor-zoom-in scale-110 select-none transition-transform duration-200 ease-out"
                                            @if($parallax) :style="`transform: scale(1.1) translate(${mouseX}px, ${mouseY}px)`" @endif
                                        >
                                    @else
                                        <img src="{{ $item['thumb'] }}" alt="{{ $item['title'] ?? '' }}" draggable="false"
                                            class="w-full h-full object-cover select-none cursor-pointer">
                                    @endif
                                </x-lightbox.trigger>
                            </div>
                        @endforeach
                    </div>

                    @if($mainArrows)
                        <div x-ref="mainPrev" class="absolute left-2 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white/80 dark:bg-slate-800/80 backdrop-blur rounded-full shadow-md flex items-center justify-center cursor-pointer text-slate-700 dark:text-slate-200 hover:bg-white hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </div>
                        <div x-ref="mainNext" class="absolute right-2 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white/80 dark:bg-slate-800/80 backdrop-blur rounded-full shadow-md flex items-center justify-center cursor-pointer text-slate-700 dark:text-slate-200 hover:bg-white hover:text-blue-600 dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ТУМБНЕЙЛЫ --}}
            @if (count($parsedItems) > 1)
                <div class="flex items-center gap-2 transition-opacity duration-300" x-ref="thumbsWrapper" style="height: 2rem; overflow: hidden; opacity: 0;">
                   @if($thumbArrows)
                        <div x-ref="thumbPrev" class="w-8 h-8 flex-shrink-0 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center cursor-pointer text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 hover:text-slate-700 dark:hover:text-slate-100 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </div>
                    @endif

                    <div x-ref="thumbsRef" class="swiper product-gallery-horizontal-thumbs flex-1 overflow-hidden">
                        <div class="swiper-wrapper">
                            @foreach ($parsedItems as $item)
                                <div class="swiper-slide relative">
                                    <img src="{{ $item['thumb'] }}" alt="" loading="lazy" draggable="false" class="w-full aspect-square object-cover rounded-lg cursor-pointer select-none transition-all duration-200 border-2 border-transparent">
                                    @if ($item['type'] === 'video')
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                            <div class="w-6 h-6 bg-black/60 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($thumbArrows)
                        <div x-ref="thumbNext" class="w-8 h-8 flex-shrink-0 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center cursor-pointer text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 hover:text-slate-700 dark:hover:text-slate-100 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </x-lightbox>
</div>