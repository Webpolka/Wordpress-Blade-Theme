{{--
Ок !

==============================================================
 WP Components: Pagination
==============================================================

Умная постраничная навигация для WordPress.
Избавляет от уродливых стандартных <ul> списков и рендерит
аккуратные Tailwind-кнопки.

--------------------------------------------------------------
 1. ГЛАВНЫЕ ФИЧИ
--------------------------------------------------------------
 - Smart Range: Показывает 1 ... 4 5 [6] 7 8 ... 12.
   (Не выводит 50 кнопок, если постов много).
 - Custom Query: Поддерживает любые WP_Query.
 - SEO & A11y: Правильные теги <nav>, aria-label, aria-current.
 - Lightning Fast: Не использует никаких JS-библиотек.

--------------------------------------------------------------
 2. ПРОПСЫ
--------------------------------------------------------------
 query (WP_Query) : Объект запроса. Если не передать, 
                    берется глобальный $wp_query.
 class (string)   : Доп. классы для обертки <nav>.

--------------------------------------------------------------
 3. ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
--------------------------------------------------------------

 // 1. Стандартная пагинация (в архивах, категориях)
 <x-pagination />

 // 2. Для кастомного цикла (WP_Query)
 $custom_query = new WP_Query([...]);
 <x-pagination :query="$custom_query" />

 // 3. Свой отступ сверху
 <x-pagination class="mt-12" />
==============================================================
--}}

@props([
    'query' => null,
    'class' => null,
])

@php
    // Если запрос не передан, берем глобальный
    $q = $query ?: $GLOBALS['wp_query'];

    $total = $q->max_num_pages ?? 1;
    $current = max(1, get_query_var('paged'));

    // Если страниц всего 1, пагинация не нужна
    if ($total <= 1) return;

    $links = [];
    $dots = false;
    $range = 1; // Сколько страниц показывать слева и справа от текущей

    // Предыдущая страница
    if ($current > 1) {
        $links[] = ['type' => 'prev', 'url' => get_pagenum_link($current - 1)];
    }

    // Цифры страниц
    for ($i = 1; $i <= $total; $i++) {
        if ($i == 1 || $i == $total || ($i >= $current - $range && $i <= $current + $range)) {
            $links[] = ['type' => 'number', 'page' => $i, 'url' => get_pagenum_link($i), 'current' => $i == $current];
            $dots = false;
        } elseif (!$dots) {
            $links[] = ['type' => 'dots'];
            $dots = true;
        }
    }

    // Следующая страница
    if ($current < $total) {
        $links[] = ['type' => 'next', 'url' => get_pagenum_link($current + 1)];
    }

    // Design System: Семантические классы для кнопок
    $btnBase = 'inline-flex shrink-0 items-center justify-center w-10 h-10 text-sm font-medium rounded-full transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 dark:ring-offset-background';
    $btnNormal = 'text-foreground hover:bg-accent hover:text-accent-foreground';
    $btnActive = 'bg-primary text-primary-foreground shadow-sm pointer-events-none';
    $btnDots = 'text-muted-foreground pointer-events-none';
@endphp

<nav class="{{ cn('flex items-center justify-center gap-1.5', $class) }}" role="navigation" aria-label="{{ __('Pagination', 'weblegko') }}">
    @foreach ($links as $link)
        @if ($link['type'] === 'prev')
            <a href="{{ $link['url'] }}" class="{{ $btnBase }} {{ $btnNormal }}" aria-label="{{ __('Previous page', 'weblegko') }}" {{ $attributes }}>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </a>
        @elseif ($link['type'] === 'next')
            <a href="{{ $link['url'] }}" class="{{ $btnBase }} {{ $btnNormal }}" aria-label="{{ __('Next page', 'weblegko') }}" {{ $attributes }}>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        @elseif ($link['type'] === 'dots')
            <span class="{{ $btnBase }} {{ $btnDots }}">...</span>
        @elseif ($link['type'] === 'number')
            <a href="{{ $link['url'] }}" class="{{ $btnBase }} {{ $link['current'] ? $btnActive : $btnNormal }}" aria-current="{{ $link['current'] ? 'page' : 'false' }}">
                {{ $link['page'] }}
            </a>
        @endif
    @endforeach
</nav>