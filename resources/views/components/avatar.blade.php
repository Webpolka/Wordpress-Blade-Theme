{{--
  OK !  

  ============================================================
  Компонент: Avatar
  Описание: Аватарка пользователя с авто-фолбэком на инициалы.
  ============================================================

  ------------------------------------------------------------
  ОСОБЕННОСТИ
  ------------------------------------------------------------
    • Если картинка не загрузилась (или нет src), показывает инициалы.
    • Цвет фона инициалов подбирается стабильно по CRC32 хешу имени.
    • Поддержка статусов (online, offline, away, busy).
    • Accessibility (aria-label, role="img").

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - src     : string – URL картинки.
    - alt     : string – Имя пользователя (для генерации инициалов).
    - size    : string – Размер (xs, sm, md, lg, xl). По умолчанию: 'md'.
    - shape   : string – Форма (circle, rounded, square). По умолчанию: 'circle'.
    - status  : string – Статус (online, offline, away, busy). По умолчанию: null.
    - class   : string – Доп. классы.

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. С картинкой:
    <x-avatar src="/img/user.jpg" alt="Иван Иванов" />

  2. Только инициалы (без src):
    <x-avatar alt="Анна Смирнова" />

  3. Большой со статусом "Отошел":
    <x-avatar src="/img/user.jpg" alt="Иван" size="lg" status="away" />

  4. Маленький для комментариев:
    <x-avatar src="/img/user.jpg" alt="Бот" size="xs" />

  5. Квадратный со статусом:
    <x-avatar src="/img/user.jpg" alt="Иван" shape="square" status="online" />

  6. Группа аватаров:
    <div class="flex -space-x-2">
        <x-avatar src="/img/user1.jpg" alt="Иван" class="ring-2 ring-white" />
        <x-avatar src="/img/user2.jpg" alt="Анна" class="ring-2 ring-white" />
        <x-avatar alt="Петр" class="ring-2 ring-white" />
    </div>
--}}

@props([
    'src'    => null,
    'alt'    => 'User',
    'size'   => 'md',
    'shape'  => 'circle',
    'status' => null,
    'class'  => null,
])

@php
    // ========================================================================
    // Инициалы
    // ========================================================================
    $words = preg_split('/\s+/', trim($alt));
    $words = array_filter($words);
    
    $initials = '';
    if (count($words) >= 2) {
        $initials = strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
    } elseif (!empty($words)) {
        $first = reset($words);
        $initials = strtoupper(mb_substr($first, 0, 1));
    }
    $initials = $initials ?: '?';

    // ========================================================================
    // Размеры и формы
    // ========================================================================
    $sizes = [
        'xs' => 'h-6 w-6 text-[10px]',
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-14 w-14 text-lg',
        'xl' => 'h-20 w-20 text-2xl',
    ];

    $shapes = [
        'circle'  => 'rounded-full',
        'rounded' => 'rounded-xl',
        'square'  => 'rounded-none',
    ];

    // ========================================================================
    // Цвета для фона инициалов (стабильный выбор по CRC32 хешу имени)
    // ========================================================================
    $colors = [
        ['bg' => 'bg-blue-500',   'text' => 'text-white'],
        ['bg' => 'bg-green-500',  'text' => 'text-white'],
        ['bg' => 'bg-red-500',    'text' => 'text-white'],
        ['bg' => 'bg-amber-500',  'text' => 'text-white'],     // ← amber вместо yellow
        ['bg' => 'bg-purple-500', 'text' => 'text-white'],
        ['bg' => 'bg-pink-500',   'text' => 'text-white'],
        ['bg' => 'bg-indigo-500', 'text' => 'text-white'],
        ['bg' => 'bg-teal-500',   'text' => 'text-white'],
        ['bg' => 'bg-cyan-500',   'text' => 'text-white'],
        ['bg' => 'bg-orange-500', 'text' => 'text-white'],
    ];
    $colorIndex = abs(crc32($alt)) % count($colors);
    $bgColor = $colors[$colorIndex]['bg'];
    $textColor = $colors[$colorIndex]['text'];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $shapeClass = $shapes[$shape] ?? $shapes['circle'];

    // ========================================================================
    // Статус
    // ========================================================================
    $statusColors = [
        'online'  => 'bg-green-500',
        'offline' => 'bg-gray-400',
        'away'    => 'bg-yellow-500',
        'busy'    => 'bg-red-500',
    ];

    // Размеры статусов (адаптивные под размер аватара)
    $statusSizes = [
        'xs' => 'h-1.5 w-1.5 border',
        'sm' => 'h-2 w-2 border',
        'md' => 'h-2.5 w-2.5 border-2',
        'lg' => 'h-3.5 w-3.5 border-2',
        'xl' => 'h-4 w-4 border-2',
    ];

    $statusClass = '';
    if (array_key_exists($status, $statusColors)) {
        $statusClass = $statusColors[$status] . ' ' . ($statusSizes[$size] ?? $statusSizes['md']) . ' border-white dark:border-gray-900';
    }

    // ========================================================================
    // Обёртка
    // ========================================================================
    $wrapperClasses = cn(
        'relative inline-flex flex-shrink-0',
        $class
    );

    $innerClasses = cn(
        'flex items-center justify-center overflow-hidden font-semibold select-none',
        $sizeClass,
        $shapeClass
    );
@endphp

<div 
    class="{{ $wrapperClasses }}"
    role="img"
    aria-label="{{ $alt }}"
    title="{{ $alt }}"
>
    {{-- Контейнер аватара с Alpine логикой --}}
    <div 
        class="{{ $innerClasses }}"
        x-data="{ 
            imgError: false, 
            hasSrc: {{ $src ? 'true' : 'false' }} 
        }"
        :class="(imgError || !hasSrc) ? '{{ $bgColor }} {{ $textColor }}' : 'bg-gray-100 dark:bg-gray-800'"
    >
        @if($src)
            <img 
                src="{{ $src }}" 
                alt="{{ $alt }}"
                x-show="!imgError"
                x-init="
                    if ($el.complete && $el.naturalWidth === 0) {
                        imgError = true;
                    }
                "
                @@load="imgError = false"
                @@error="imgError = true"
                class="h-full w-full object-cover"
                loading="lazy"
            >
        @endif
        
        {{-- Инициалы (фолбэк) --}}
        <span 
            x-show="{{ $src ? 'imgError' : 'true' }}"
            @if($src) x-cloak @endif
            class="leading-none"
        >
            {{ $initials }}
        </span>
    </div>

    {{-- Статус-индикатор --}}
    @if($statusClass)
        <span             
            class="absolute bottom-0 right-0 translate-x-[15%] translate-y-[15%] rounded-full {{ $statusClass }}"          
            aria-label="{{ __('Status', 'weblegko') }}: {{ $status }}"
        ></span>
    @endif
</div>