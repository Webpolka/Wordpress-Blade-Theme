{{--
  ============================================================
  Компонент: Lightbox
  Описание: Универсальный лайтбокс для фото и видео (Слайдер).
  ============================================================

  ------------------------------------------------------------
  ОСОБЕННОСТИ (Как в Shopify Dawn)
  ------------------------------------------------------------
    • Deferred Media: Видео (YouTube/Vimeo/MP4) грузятся ТОЛЬКО 
      когда слайд активен. При перелистывании старое видео 
      уничтожается, чтобы не играло в фоне.
    • Auto-Detect: PHP сам определяет тип ссылки (Картинка, 
      YouTube, Vimeo, HTML5 Video).
    • Slider: Поддержка перелистывания стрелками клавиатуры 
      и кнопками.
    • Zoom: Картинки можно приблизить кликом.

  ------------------------------------------------------------
  ПРОПСЫ
  ------------------------------------------------------------
    - items : array  – Массив ссылок (строк) или объектов [{src, poster}]
    - class : string – Доп. классы.
    - zoom : boolean 

  ============================================================
  ПРИМЕР ИСПОЛЬЗОВАНИЯ
  ============================================================

  @php
    $media = [
        '/images/photo1.jpg',
        'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        '/videos/promo.mp4'
    ];
  @endphp

  <x-lightbox :items="$media" :zoom="false">
    <x-lightbox.trigger index="0">
        <img src="/images/photo1-thumb.jpg" class="cursor-pointer">
    </x-lightbox.trigger>
  </x-lightbox>

Как теперь это использовать в WooCommerce / WP (DX):

Вот пример, как разработчик соберет галерею из картинок товара (attachment) и добавит видео с YouTube с заголовками:

  @php
    // Допустим, мы собрали массив картинок из медиатеки WP
    $gallery_images = [
        ['src' => wp_get_attachment_url(123), 'title' => get_the_title(123)],
        ['src' => wp_get_attachment_url(124), 'alt' => 'Детализация товара'],
    ];
    
    // Добавляем видео с YouTube
    $gallery_images[] = [
        'src' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'title' => 'Обзор товара от производителя'
    ];
@endphp

<x-lightbox :items="$gallery_images">
    -- Картинка превью №1 (без кнопки Play) --
    <x-lightbox.trigger index="0">
        <img src="/thumb1.jpg" class="w-32 h-32 object-cover rounded">
    </x-lightbox.trigger>

    -- Картинка превью №2 (index="2" — это YouTube видео, ставим Play) --
    <x-lightbox.trigger index="2" :play="true">
        <img src="/video-thumb.jpg" class="w-32 h-32 object-cover rounded">
    </x-lightbox.trigger>
</x-lightbox>


вот  массив для пробы разных видеохостингов:
   @@php
        $media = [
                [
                    'src' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb', 
                    'title' => 'Просто красивая картинка (Можно задвоить клик для зума)'
                ],
                [
                    'src' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 
                    'title' => 'YouTube видео (Rick Astley)'
                ],
                [
                    'src' => 'https://vimeo.com/76979871', 
                    'title' => 'Vimeo видео (The Mountain)'
                ],
                [
                    'src' => 'https://rutube.ru/video/9196c1067e4d925a4e5899a23c00c63b/?r=wd', 
                    'title' => 'Rutube видео'
                ],
                [
                    'src' => 'https://vk.com/video-211869299_456240914', 
                    'title' => 'VK Видео'
                ],
                 [
                    'src' => 'https://www.dailymotion.com/video/x9lygl2', // <-- DAILYMOTION
                    'title' => 'Dailymotion видео'
                ],
                [
                    'src' => Vite::asset('resources/video/dj-lovely.mp4'), 
                    'title' => 'Просто локальный видосик'
                ]        
            ];      
    @@endphp

        <x-lightbox :items="$media">
            <x-lightbox.trigger index="0">
                <img src="{{ Vite::asset ('resources/images/webp/video-poster.webp') }}" class="cursor-pointer">
            </x-lightbox.trigger>
        </x-lightbox>


--}}

@props([
    'items' => [],
    'class' => null,
    'zoom' => true,
])
@php
    // Умный парсер ссылок и метаданных
    $parsedItems = array_map(function ($item) {
        if (is_string($item)) {
            $item = ['src' => $item];
        }

        $src = $item['src'] ?? '';
        // НОВОЕ: Принимаем 'thumb' как постер (приходит из Product Gallery)
        $poster = $item['poster'] ?? $item['thumb'] ?? '';
        $title = $item['title'] ?? ($item['caption'] ?? ($item['alt'] ?? ''));
        
        // НОВОЕ: Проверяем, не передан ли уже тип (например, из Product Gallery)
        $type = $item['type'] ?? 'image';

        // Если тип уже видео и ссылка не локальный файл, то это iframe
        if ($type === 'video') {
            $type = preg_match('/\.(mp4|webm|ogg)$/', $src) ? 'html5' : 'iframe';
        } 
        // Если тип не видео и не iframe/html5, пытаемся распарсить (для прямого вызова Lightbox)
        elseif ($type !== 'iframe' && $type !== 'html5') {
            if (preg_match('/youtube\.com\/(?:watch\?v=|embed\/)([a-zA-Z0-9_-]+)/', $src, $m) || preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $src, $m)) {
                $type = 'iframe';
                // Меняем src только если это не embed-ссылка (чтобы не дублировать параметры)
                if (strpos($src, 'youtube.com/embed/') === false) {
                    $src = 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1&mute=1&rel=0';
                }
            } elseif (preg_match('/vimeo\.com\/(?:video\/)?([0-9]+)/', $src, $m)) {
                $type = 'iframe';
                if (strpos($src, 'player.vimeo.com/video/') === false) {
                    $src = 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1&muted=1';
                }
            } elseif (preg_match('/rutube\.ru\/(?:video|play\/embed|shorts)\/([a-zA-Z0-9_-]+)/', $src, $m)) {
                $type = 'iframe';
                if (strpos($src, 'rutube.ru/play/embed/') === false) {
                    $src = 'https://rutube.ru/play/embed/' . $m[1] . '?autoplay=1';
                }
            } elseif (preg_match('/vk\.com\/video(-?[0-9]+)_([0-9]+)/', $src, $m)) {
                $type = 'iframe';
                if (strpos($src, 'vk.com/video_ext.php') === false) {
                    $src = 'https://vk.com/video_ext.php?oid=' . $m[1] . '&id=' . $m[2] . '&hd=2&autoplay=1';
                }
            } elseif (preg_match('/dailymotion\.com\/(?:video|embed\/video)\/([a-zA-Z0-9]+)/', $src, $m)) {
                $type = 'iframe';
                if (strpos($src, 'dailymotion.com/embed/video/') === false) {
                    $src = 'https://www.dailymotion.com/embed/video/' . $m[1] . '?autoplay=1';
                }
            } elseif (preg_match('/\.(mp4|webm|ogg)$/', $src)) {
                $type = 'html5';
            } else {
                $type = 'image';
            }
        }

        return ['type' => $type, 'src' => $src, 'poster' => $poster, 'title' => $title];
    }, $items);

    $config = [
        'items' => $parsedItems,
        'zoom'  => (bool) $zoom
    ];
@endphp

<div x-data="lightbox({{ json_encode($config) }})" @keydown.window="if(isOpen) { 
        if ($event.key === 'Escape') { close(); }
    }"
    class="{{ cn($class) }}">
    {{ $slot }}
    <x-lightbox.content />
</div>