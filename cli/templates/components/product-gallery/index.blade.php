{{--
  ============================================================
  Компонент: Product Gallery (Адаптивная обертка)
  Описание: Автоматически рендерит вертикальную галерею на 
             десктопе и горизонтальную на мобилках.
  ============================================================

  ------------------------------------------------------------
  ОСОБЕННОСТИ
  ------------------------------------------------------------
    • Меняет макет на лету при ресайзе окна (через Alpine x-if).
    • Безопасно для памяти: уничтожает старый Swiper перед 
      созданием нового.
    • Принимает все те же пропсы, что и вложенные галереи.
  ------------------------------------------------------------
  ПРОПСЫ 
  ------------------------------------------------------------
  (Параметры) Все пропсы идентичны <x-product-gallery-horizontal/vertical>.

    - images        (array)  – Массив картинок и видео.
    - class         (string) – Доп. классы для обертки.
    - mainArrows    (bool)   – Показывать стрелки на главном слайдере. По умолчанию: true.
    - thumbArrows   (bool)   – Показывать стрелки на слайдере тумбнейлов. По умолчанию: true.
    - parallax      (bool)   – Включить эффект параллакса. По умолчанию: true.
    - lightBoxZoom  (bool)   – Опция зум в лайтбоксе при двойном клике.
    - aspect        (string) – Tailwind класс для главного слайдера (apsect-square, aspect-[2/1]).
    - mainSettings  (array)  – Доп. настройки Swiper для главного слайдера (merge с дефолтными).
    - thumbSettings (array)  – Доп. настройки Swiper для слайдера тумбнейлов (merge с дефолтными).

   ДОБАВЛЕННЫЙ ПРОПС:
    - desktopBreakpoint (int) - ширина экрана в px, с которой 
      включается вертикальная галерея. По умолчанию: 768 (md).

  ============================================================
  КАК ЗАДАТЬ РАЗНЫЕ ПРООПОРЦИИ ГАЛЛЕРЕИ НА БРЕКПОИНТАХ ?
  ============================================================

  Передаем в aspect таилвинд классы. Вот простой пример для наглядности:
  <x-product-gallery :images="$gallery_media" aspect="aspect-square md:aspect-[15/10] xl:aspect-[2/1]" />

  ============================================================
  ПРИМЕР ИСПОЛЬЗОВАНИЯ
  ============================================================

  Передай thumb если выводится плейсхолдер, потому что не все видеохостинги отдают постер к видео,
  а для картинок используй функции вордпресс для получения тумбнейлов.

  Пример получения:
    @@php
        $gallery_media = [];

        // Если у поста есть ID картинок в галерее (например, из ACF или meta)
        $image_ids = get_field('gallery'); // или get_post_meta($post_id, 'gallery', true);

        if ($image_ids) {
            foreach ($image_ids as $image_id) {
                // Получаем все размеры
                $large = wp_get_attachment_image_src($image_id, 'large');
                $medium = wp_get_attachment_image_src($image_id, 'medium');
                $thumbnail = wp_get_attachment_image_src($image_id, 'thumbnail');
                $full = wp_get_attachment_image_src($image_id, 'full');
                
                // Альт и заголовок
                $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                $title = get_the_title($image_id);
                
                $gallery_media[] = [
                    'src' => $large[0] ?? $full[0], // large или full
                    'thumb' => $thumbnail[0] ?? $medium[0] ?? $full[0], // thumb как fallback
                    'title' => $title ?: 'Изображение'                   
                ];
            }
        }
    @@endphp

   Или просто передадим статичный массив для примера (парсер вытянет сам тумбнейлы где возможно или подставит плейхолдеры):

   @@php
        $gallery_media = [
                [
                    'src' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb', 
                    'title' => 'Просто красивая картинка (Можно задвоить клик для зума)',
                    'thumb' => ''
                ],
                [
                    'src' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 
                    'title' => 'YouTube видео (Rick Astley)',
                    'thumb' => ''
                ],
                [
                    'src' => 'https://vimeo.com/76979871', 
                    'title' => 'Vimeo видео (The Mountain)',
                    'thumb' => ''
                ],
                [
                    'src' => 'https://rutube.ru/video/9196c1067e4d925a4e5899a23c00c63b/?r=wd', 
                    'title' => 'Rutube видео',
                    'thumb' => ''
                ],
                [
                    'src' => 'https://vk.com/video-211869299_456240914', 
                    'title' => 'VK Видео',
                    'thumb' => ''
                ],
                 [
                    'src' => 'https://www.dailymotion.com/video/x9lygl2', // <-- DAILYMOTION
                    'title' => 'Dailymotion видео',
                    'thumb' => ''
                ],
                [
                    'src' => Vite::asset('resources/video/dj-lovely.mp4'), 
                    'title' => 'Просто локальный видосик',
                    'thumb' => ''
                ]        
            ];      
    @@endphp

  // Базовый вызов (переключение на 768px)
  <x-product-gallery :images="$gallery_media" />

  // Включать вертикальную только на больших экранах (1024px)
  <x-product-gallery :images="$gallery_media" desktop-breakpoint="1024" />

  // Включать только вертикально 
  <x-product-gallery :images="$gallery_media" desktopBreakpoint="false" />
  //или
  <x-product-gallery.vertical :images="$gallery_media" />

  // Включать только горизонтально 
  <x-product-gallery.horizontal :images="$gallery_media" />

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
    'desktopBreakpoint' => 768,
])

<div 
    x-data="{ 
        isDesktop: window.innerWidth >= {{ $desktopBreakpoint }},
        currentSlide: 0 
    }" 
    :data-current-slide="currentSlide"
    @gallery-sync-slide="currentSlide = $event.detail.index"
    @resize.window.debounce.150ms="isDesktop = window.innerWidth >= {{ $desktopBreakpoint }}"
    x-cloak
    class="{{ $class }}"
>
    {{-- ДЕСКТОП (Вертикальная галерея) --}}
    <template x-if="isDesktop">
        <x-product-gallery.vertical 
            :images="$images" 
            :main-arrows="$mainArrows" 
            :thumb-arrows="$thumbArrows" 
            :parallax="$parallax" 
            :light-box-zoom="$lightBoxZoom" 
            :aspect="$aspect"
            :main-settings="$mainSettings"
            :thumb-settings="$thumbSettings"
        />
    </template>

    {{-- МОБИЛКА (Горизонтальная галерея) --}}
    <template x-if="!isDesktop">
        <x-product-gallery.horizontal 
            :images="$images" 
            :main-arrows="$mainArrows" 
            :thumb-arrows="$thumbArrows" 
            :parallax="$parallax" 
            :light-box-zoom="$lightBoxZoom" 
            :aspect="$aspect"
            :main-settings="$mainSettings"
            :thumb-settings="$thumbSettings"
        />
    </template>
</div>