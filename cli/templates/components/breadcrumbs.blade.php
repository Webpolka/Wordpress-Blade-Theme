{{--
OK !

==============================================================
 WP Components: Breadcrumbs (Хлебные крошки)
==============================================================

Умная навигационная цепочка. Незаменима для SEO ( микроразметка 
через плагины) и UX (юзер видит, где он находится).

--------------------------------------------------------------
 1. ГЛАВНЫЕ ФИЧИ
--------------------------------------------------------------
 - Auto-Detect: Автоматически находит данные Rank Math, Yoast 
   или WooCommerce. Если их нет — строит крошки сам (WP Native).
 - Scrollable: На мобильных крошки не переносятся на новую 
   строку, а аккуратно скроллятся пальцем (scrollbar-none).
 - Clean SVG: Вместо уродливых символов '>' используются 
   аккуратные иконки-шевроны.

--------------------------------------------------------------
 2. ПРОПСЫ
--------------------------------------------------------------
 items (array)  : Ручная передача массива крошек. 
                  Формат: [['title' => 'Главная', 'url' => '/'], ...]
                  Если не передать, сработает авто-режим.
 class (string) : Доп. классы для обертки <nav>.

--------------------------------------------------------------
 3. ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
--------------------------------------------------------------

 // 1. Автоматический вывод (в header.blade.php)
 // Компонент сам поймет, категория это, товар или страница
 <x-breadcrumbs class="py-4" />

 // 2. Ручная передача (если нужна специфика)
 <x-breadcrumbs :items="[
     ['title' => 'Главная', 'url' => '/'],
     ['title' => 'Блог', 'url' => '/blog'],
     ['title' => 'Текущая статья']
 ]" />

 // 3. Вывод вместе с заголовком архива
 <div class="container">
     <x-breadcrumbs class="pt-4" />
     <h1 class="text-3xl mt-2">{{ get_the_archive_title() }}</h1>
 </div>
==============================================================
--}}

@props([
    'items' => null,
    'class' => null,
])

@php
    $crumbs = [];

    // 1. Приоритет: ручная передача
    if (is_array($items) && !empty($items)) {
        $crumbs = $items;
    } 
    // 2. Rank Math SEO
    elseif (function_exists('rank_math_get_breadcrumbs')) {
        $rankMathCrumbs = rank_math_get_breadcrumbs();
        if (!empty($rankMathCrumbs)) {
            foreach ($rankMathCrumbs as $c) {
                $crumbs[] = ['title' => $c['title'], 'url' => $c['url'] ?? ''];
            }
        }
    } 
    // 3. WooCommerce ( Берем массив через родной класс WC_Breadcrumb)
    elseif (class_exists('WC_Breadcrumb')) {
        $wc_breadcrumb = new WC_Breadcrumb();
        // Добавляем главную страницу магазина/сайта
        $wc_breadcrumb->add_crumb(__('Home', 'weblegko'), apply_filters('woocommerce_breadcrumb_home_url', home_url('/')));
        
        $wooCrumbs = $wc_breadcrumb->generate();
        if (!empty($wooCrumbs)) {
            foreach ($wooCrumbs as $c) {
                $crumbs[] = ['title' => $c[0], 'url' => $c[1] ?? ''];
            }
        }
    } 
    // 4. Нативный фолбэк WordPress (если плагинов нет)
    else {
        $crumbs[] = ['title' => __('Home', 'weblegko'), 'url' => home_url('/')];

        if (is_singular('post')) {
            $cats = get_the_category();
            if (!empty($cats)) {
                $crumbs[] = ['title' => $cats[0]->name, 'url' => get_term_link($cats[0])];
            }
            $crumbs[] = ['title' => get_the_title(), 'url' => ''];
        } elseif (is_category() || is_tag() || is_tax()) {
            $crumbs[] = ['title' => single_term_title('', false), 'url' => ''];
        } elseif (is_page()) {
            $crumbs[] = ['title' => get_the_title(), 'url' => ''];
        } elseif (is_search()) {
            $crumbs[] = ['title' => __('Search:', 'weblegko') . ' ' . get_search_query(), 'url' => ''];
        } elseif (is_404()) {
            $crumbs[] = ['title' => '404', 'url' => ''];
        }
    }

    // Если крошек нет, не выводим ничего
    if (empty($crumbs)) return;
@endphp

<nav aria-label="{{ __('Breadcrumbs', 'weblegko') }}" class="{{ cn('w-full', $class) }}">
    <ol class="flex items-center gap-1.5 text-sm text-muted-foreground overflow-x-auto whitespace-nowrap scrollbar-none">
        @foreach ($crumbs as $i => $crumb)
            @php $isLast = $i === array_key_last($crumbs); @endphp
            
            <li class="flex items-center gap-1.5 shrink-0">
                @if (!$isLast && !empty($crumb['url']))
                    <a href="{{ $crumb['url'] }}" class="hover:text-foreground no-underline transition-colors">
                        {{ $crumb['title'] }}
                    </a>
                    {{-- Разделитель (Шеврон) --}}
                    <svg class="w-4 h-4 text-muted-foreground/40 flex-shrink-0 select-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                @else
                    <span class="font-medium text-foreground" aria-current="page">
                        {{ $crumb['title'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>