{{--
  ============================================================
  КОМПОНЕНТ: Slider (Swiper.js)
  Версия: 2.0.0
  ============================================================
  
  ПРОПСЫ:
    slidesPerView       - число слайдов на экране (по умолч. 1)
    spaceBetween        - отступ между слайдами в px (по умолч. 16)
    loop                - зацикленный режим (true/false, по умолч. false)
    autoplay            - автоплей (true = 3000мс, число = мс)
    navigation          - включить стрелки (true/false, по умолч. true)
    pagination          - включить точки (true/false, по умолч. true)
    breakpoints         - адаптивные настройки (массив)
    settings            - дополнительные параметры Swiper (массив)
    class               - CSS классы для обёртки
    id                  - ID слайдера (для external режима)
    paginationPosition  - 'default' (внутри) или 'external' (снаружи)
    navigationPosition  - 'default' (внутри) или 'external' (снаружи)
    overflow            - string – CSS класс overflow для контейнера слайдера.
        По умолчанию: 'overflow-hidden'
        Примеры: 'overflow-visible', 'md:overflow-hidden overflow-visible'
  
  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================
  
  1. Базовый слайдер (всё по умолчанию):
  
     <x-slider>
         <x-slider.slide>Слайд 1</x-slider.slide>
         <x-slider.slide>Слайд 2</x-slider.slide>
         <x-slider.slide>Слайд 3</x-slider.slide>
     </x-slider>
  
  2. Три слайда на десктопе, один на мобиле:

     <x-slider 
         :slides-per-view="3" 
         :breakpoints="[768 => ['slidesPerView' => 1]]"
     >
         <x-slider.slide>Слайд 1</x-slider.slide>
         <x-slider.slide>Слайд 2</x-slider.slide>
         <x-slider.slide>Слайд 3</x-slider.slide>
     </x-slider>
  
  3. Автоплей каждые 5 секунд:

     <x-slider :autoplay="5000" :loop="true">
         <x-slider.slide>Слайд 1</x-slider.slide>
         <x-slider.slide>Слайд 2</x-slider.slide>
     </x-slider>
  
  4. Внешняя пагинация (точки снаружи):

     <div data-swiper-pagination="my-slider" class="flex justify-center gap-2 mt-4"></div>
     
     <x-slider 
         id="my-slider" 
         pagination-position="external"
     >
         <x-slider.slide>Слайд 1</x-slider.slide>
         <x-slider.slide>Слайд 2</x-slider.slide>
     </x-slider>
  
  5. Внешние стрелки (кнопки снаружи):

     <div class="flex gap-2 mb-4">
         <button data-swiper-prev="catalog">←</button>
         <button data-swiper-next="catalog">→</button>
     </div>
     
     <x-slider 
         id="catalog" 
         navigation-position="external"
         :slides-per-view="4"
     >
         <x-slider.slide>Товар 1</x-slider.slide>
         <x-slider.slide>Товар 2</x-slider.slide>
     </x-slider>
  
  6. Всё внешнее (стрелки + пагинация):

     <div class="flex justify-between mb-4">
         <h2>Отзывы</h2>
         <div class="flex gap-2">
             <button data-swiper-prev="reviews">←</button>
             <button data-swiper-next="reviews">→</button>
         </div>
     </div>
     
     <x-slider 
         id="reviews"
         pagination-position="external"
         navigation-position="external"
         :slides-per-view="3"
     >
         <x-slider.slide>Отзыв 1</x-slider.slide>
         <x-slider.slide>Отзыв 2</x-slider.slide>
     </x-slider>
     
     <div data-swiper-pagination="reviews" class="flex justify-center gap-2 mt-6"></div>
  
  7. Эффект затемнения (fade):

     <x-slider 
         :settings="['effect' => 'fade', 'speed' => 800]"
         :loop="true"
     >
         <x-slider.slide>Слайд 1</x-slider.slide>
         <x-slider.slide>Слайд 2</x-slider.slide>
     </x-slider>
  
  8. Полный контроль с кастомными классами:

     <x-slider 
         class="my-slider-wrapper"
         :slides-per-view="4"
         :space-between="24"
         :loop="true"
         :breakpoints="[
             640 => ['slidesPerView' => 2, 'spaceBetween' => 12],
             1024 => ['slidesPerView' => 3, 'spaceBetween' => 20],
             1280 => ['slidesPerView' => 4, 'spaceBetween' => 24]
         ]"
     >
         @foreach($items as $item)
             <x-slider.slide>
                 <div class="p-4 bg-white rounded-lg shadow">
                     {{ $item->name }}
                 </div>
             </x-slider.slide>
         @endforeach
     </x-slider>

       ------------------------------------------------------------
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ C OVERFLOW
  ------------------------------------------------------------

  1. Базовый слайдер (overflow-hidden по умолчанию):
    <x-slider>
        <x-slider.slide>Слайд 1</x-slider.slide>
        <x-slider.slide>Слайд 2</x-slider.slide>
    </x-slider>

  2. Overflow visible (слайды выходят за границы):

    <x-slider overflow="!overflow-visible">
        <x-slider.slide>Слайд 1</x-slider.slide>
        <x-slider.slide>Слайд 2</x-slider.slide>
    </x-slider>

  3. Адаптивный overflow (мобилка visible, десктоп hidden):

    <x-slider overflow="md:!overflow-hidden !overflow-visible">
        <x-slider.slide>Слайд 1</x-slider.slide>
        <x-slider.slide>Слайд 2</x-slider.slide>
    </x-slider>

  4. С внешними контролами и адаптивным overflow:

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Товары</h2>
        <div class="flex gap-2">
            <button data-swiper-prev="catalog" class="p-2 bg-gray-100 rounded-full">←</button>
            <button data-swiper-next="catalog" class="p-2 bg-gray-100 rounded-full">→</button>
        </div>
    </div>
    
    <x-slider 
        id="catalog" 
        navigation-position="external" 
        overflow="md:!overflow-hidden !overflow-visible"
        :slides-per-view="1.2"
        :space-between="16"
    >
        <x-slider.slide>Товар 1</x-slider.slide>
        <x-slider.slide>Товар 2</x-slider.slide>
        <x-slider.slide>Товар 3</x-slider.slide>
    </x-slider>

     
  ============================================================
  КАСТОМНЫЕ БУЛЛЕТЫ
  ============================================================
  
  Используй customType для предустановленных стилей буллетов:
  
    <x-slider :settings="[
        'pagination' => ['customType' => 'lines']
    ]">
        ...
    </x-slider>
  
  Доступные типы:
    - 'numbered'       – Буллеты с номерами (1, 2, 3...)
    - 'numberedActive' – Буллеты с номерами и hover эффектом
    - 'lines'          – Буллеты в виде линий
    - 'dots'           – Буллеты в виде точек с анимацией
    - 'squares'        – Буллеты в виде квадратов
    - 'diamonds'       – Буллеты в виде ромбов
  
  Примеры:
  
  1. Буллеты с номерами:
    <x-slider :settings="[
        'pagination' => ['customType' => 'numbered']
    ]">
        <x-slider.slide>Слайд 1</x-slider.slide>
        <x-slider.slide>Слайд 2</x-slider.slide>
    </x-slider>
  
  2. Буллеты в виде линий:
    <x-slider :settings="[
        'pagination' => ['customType' => 'lines']
    ]">
        <x-slider.slide>Слайд 1</x-slider.slide>
        <x-slider.slide>Слайд 2</x-slider.slide>
    </x-slider>
  
  3. Буллеты в виде квадратов:
    <x-slider :settings="[
        'pagination' => ['customType' => 'squares']
    ]">
        <x-slider.slide>Слайд 1</x-slider.slide>
        <x-slider.slide>Слайд 2</x-slider.slide>
    </x-slider>
  
  4. Буллеты в виде ромбов:
    <x-slider :settings="[
        'pagination' => ['customType' => 'diamonds']
    ]">
        <x-slider.slide>Слайд 1</x-slider.slide>
        <x-slider.slide>Слайд 2</x-slider.slide>
    </x-slider>
  
  5. С внешними контролами и кастомными буллетами:
    <div data-swiper-pagination="gallery" class="flex justify-center gap-2 mb-4"></div>
    
    <x-slider 
        id="gallery" 
        pagination-position="external"
        :settings="[
            'pagination' => ['customType' => 'numberedActive']
        ]"
    >
        <x-slider.slide>Слайд 1</x-slider.slide>
        <x-slider.slide>Слайд 2</x-slider.slide>
    </x-slider>

  
  ============================================================
  ВАЖНО: Передача массивов
  ============================================================
  
  Используйте двоеточие (:) перед атрибутом:
    :breakpoints="[768 => ['slidesPerView' => 2]]"
    :settings="['effect' => 'fade', 'speed' => 800]"
  
  ============================================================
  КАСТОМНАЯ СТИЛИЗАЦИЯ
  ============================================================
  
  Классы для стилизации:
    .wp-swiper-root     - обёртка
    .wp-swiper          - контейнер слайдера
    .wp-swiper-prev     - кнопка "назад"
    .wp-swiper-next     - кнопка "вперёд"
    .swiper-pagination  - пагинация
    .swiper-pagination-bullet - точки пагинации
  
  Пример стилизации точек:
    .wp-swiper .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: #ccc;
    }
    .wp-swiper .swiper-pagination-bullet-active {
        background: #3b82f6;
    }
--}}

@props([
    'slidesPerView'        => 1,
    'spaceBetween'         => 16,
    'loop'                 => false,
    'autoplay'             => false,
    'navigation'           => true,
    'pagination'           => true,
    'breakpoints'          => [],
    'settings'             => [],
    'class'                => null,
    'id'                   => 'swiper-' . \Illuminate\Support\Str::random(8),
    'paginationPosition'   => 'default',  // 'default' | 'external'
    'navigationPosition'   => 'default',  // 'default' | 'external'
      // Overflow для контейнера слайдера
    'overflow'             => 'overflow-hidden',
])

@php
    $config = [
        'slidesPerView' => $slidesPerView,
        'spaceBetween'  => (int) $spaceBetween,
        'loop'          => (bool) $loop,
        'navigation'    => (bool) $navigation,
        'pagination'    => (bool) $pagination,
    ];

    if ($autoplay) {
        $config['autoplay'] = is_bool($autoplay) 
            ? ['delay' => 3000, 'disableOnInteraction' => false]
            : ['delay' => (int) $autoplay, 'disableOnInteraction' => false];
    }

    if (!empty($breakpoints) && is_array($breakpoints)) {
        $config['breakpoints'] = $breakpoints;
    }

    if (!empty($settings) && is_array($settings)) {
        $config = array_merge($config, $settings);
    }

    $paginationIsDefault = $paginationPosition === 'default' && $pagination;
    $navigationIsDefault = $navigationPosition === 'default' && $navigation;
    
    $btnClasses = 'cursor-pointer p-2 bg-gray-100 dark:bg-gray-800 rounded-full shadow-sm text-blue-600 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors';
@endphp

<div class="wp-swiper-root {{ $class }}">
    {{-- САМ СЛАЙДЕР --}}
    <div 
        id="{{ $id }}" 
        class="swiper wp-swiper relative {{ $overflow }}" 
        data-swiper-config='@json($config)'
    >
        <div class="swiper-wrapper">
            {{ $slot }}
        </div>

        {{-- Пагинация внутри (default) --}}
        @if($paginationIsDefault)
            <div class="swiper-pagination"></div>
        @endif

        {{-- Навигация внутри (default) --}}
        @if($navigationIsDefault)
            <div class="wp-swiper-prev absolute left-2 top-1/2 -translate-y-1/2 z-10 {{ $btnClasses }} bg-white/80 dark:bg-gray-800/80 hover:bg-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </div>
            <div class="wp-swiper-next absolute right-2 top-1/2 -translate-y-1/2 z-10 {{ $btnClasses }} bg-white/80 dark:bg-gray-800/80 hover:bg-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        @endif
    </div>
</div>