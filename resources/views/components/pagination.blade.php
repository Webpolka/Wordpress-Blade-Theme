{{--
  ============================================================
  Компонент: Pagination
  Описание: Умная постраничная навигация для WordPress.

  Для использования примеров убери лишнюю @ !!! 
  ============================================================

  Заменяет уродливые стандартные <ul> списки WP на красивые
  Tailwind-кнопки. Умеет прятать лишние страницы за троеточием.

  ------------------------------------------------------------
  ГЛАВНЫЕ ФИЧИ
  ------------------------------------------------------------
  • Smart Range: Показывает 1 ... 4 5 [6] 7 8 ... 12.
  • Custom Query: Поддерживает любые WP_Query (не только глобальный).
  • SEO & A11y: Правильные теги <nav>, aria-label, aria-current.
  • Zero JS: Работает на чистом PHP + HTML.

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
  query (WP_Query) : Объект запроса. Если не передать, 
                     берется глобальный $wp_query.
  class (string)   : Доп. классы для обертки <nav>.

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ (БЫСТРЫЙ СТАРТ)
  ============================================================

  1. СТАНДАРТНЫЙ ЦИКЛ WP (Архивы, Категории, Блог):
  -------------------------------------------------
  В файлах вроде index.blade.php или archive.blade.php 
  просто вставь компонент после цикла:

    @@while(have_posts()) @@php(the_post())
        @@include('partials.content')
    @@endwhile
    
    <x-pagination class="mt-12" />


  2. КАСТОМНЫЙ ЗАПРОС (WP_Query):
  -------------------------------------------------
  Если ты создал свой запрос (например, выводит 6 постов 
  на странице front-page.blade.php), передай объект запроса 
  в пропс :query:

    @@php
        $custom_query = new WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 6,
            'paged' => get_query_var('paged') ?: 1
        ]);
    @@endphp

    @@while($custom_query->have_posts()) @@php($custom_query->the_post())
       @@include('partials.content')
    @@endwhile
    @@php(wp_reset_postdata())
    
    <x-pagination :query="$custom_query" class="mt-12" />

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

    // Цифры страниц (умная сборка с троеточиями)
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

    // Классы кнопок (Tailwind)
    $btnBase = 'inline-flex items-center justify-center w-10 h-10 text-sm font-medium rounded-full transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500';
    $btnNormal = 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800';
    $btnActive = 'bg-blue-600 text-white shadow-sm pointer-events-none';
    $btnDots = 'text-gray-400 pointer-events-none';
@endphp

<nav class="{{ cn('flex items-center justify-center gap-1.5', $class) }}" role="navigation" aria-label="Постраничная навигация">
    @foreach ($links as $link)
        @if ($link['type'] === 'prev')
            <a href="{{ $link['url'] }}" class="{{ $btnBase }} {{ $btnNormal }}" aria-label="Предыдущая страница" {{ $attributes }}>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </a>
        @elseif ($link['type'] === 'next')
            <a href="{{ $link['url'] }}" class="{{ $btnBase }} {{ $btnNormal }}" aria-label="Следующая страница" {{ $attributes }}>
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