{{--
  Ok !

  ============================================================
  Компонент: Post Navigation
  Описание: Навигация для перехода между записями WordPress.
  ============================================================

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - mode        : string – Режим отображения.
                      'flow' (по умолчанию) – в потоке страницы
                      'fixed' – плавающая по бокам
                                Desktop: hover для выезда
                                Mobile: тап на стрелку для выезда
    - showTitle   : bool – Показывать заголовки (по умолчанию true)
    - showExcerpt : bool – Показывать отрывок (по умолчанию true)
    - showThumb   : bool – Показывать картинку (по умолчанию true)
    - class       : string – Доп. классы для <nav>

  ------------------------------------------------------------
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ------------------------------------------------------------

  1. Стандартный вид под статьёй (в потоке):
    <x-post-navigation mode="flow" class="mt-16" />

  2. Без excerpt и thumbnail:
    <x-post-navigation :show-excerpt="false" :show-thumb="false"  />

  3. Без title с классами:
  <x-post-navigation :show-title="false" class="mt-16 border-t pt-8"/>

  3. Плавающая навигация по бокам:
    <x-post-navigation mode="fixed" />
    

    Desktop: hover на плашку → выезжает
    Mobile: тап на стрелку → выезжает, тап на контент → переход

  ------------------------------------------------------------
  SEO & ACCESSIBILITY
  ------------------------------------------------------------
    - rel="prev" / rel="next" для поисковиков
    - aria-label на ссылках и кнопках
    - role="navigation" на <nav>
    - aria-label на <nav>

--}}

@props([
    'mode'        => 'flow',
    'showTitle'   => true,
    'showExcerpt' => true,
    'showThumb'   => true,
    'class'       => null,
])

@php
    // ========================================================================
    // Получение данных постов
    // ========================================================================
    $prevPost = get_previous_post();
    $nextPost = get_next_post();

    // Если нет ни одного поста — не рендерим ничего
    if (!$prevPost && !$nextPost) {
        return;
    }

    // Хелпер для сборки данных о посте
    $getPostData = function ($post) {
        if (!$post) return null;
        
        $title = get_the_title($post);
        if (empty($title)) {
            $title = __('(No title)', 'weblegko');
        }

        return [
            'url'     => get_permalink($post),
            'title'   => $title,
            'thumb'   => get_the_post_thumbnail_url($post, 'thumbnail') ?: null,
            'excerpt' => wp_trim_words(get_the_excerpt($post), 12, '...'),
        ];
    };

    $prev = $getPostData($prevPost);
    $next = $getPostData($nextPost);

    // ========================================================================
    // Классы обёртки
    // ========================================================================
    if ($mode === 'flow') {
        $wrapperClasses = 'grid grid-cols-1 md:grid-cols-2 gap-3';
    } elseif ($mode === 'fixed') {
        // Fixed режим работает только на desktop (md+)
        $wrapperClasses = 'flex fixed inset-0 z-40 pointer-events-none items-center justify-between';
    } else {
        $wrapperClasses = 'grid grid-cols-1 md:grid-cols-2 gap-3';
    }

    $wrapperClasses = cn($wrapperClasses, $class);

    // ========================================================================
    // Визуальные классы карточек (Стандартизировано под bg-gray-800)
    // ========================================================================
    $flowCardClasses = cn(
        'gap-5 p-2 sm:p-4 rounded-xl border',
        'border-gray-200 dark:border-gray-700',
        'bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100',
        'shadow-sm transition-all duration-300',
        'hover:shadow-lg hover:-translate-y-1 hover:no-underline',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900'
    );

    // Для fixed: 3.5rem = 56px (ширина стрелки + padding)
    $fixedCardClasses = cn(
        'gap-3 p-2 md:p-4 border',
        'border-gray-200 dark:border-gray-700',
        'bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100',
        'shadow-lg transition-transform duration-300 ease-out hover:no-underline',
        'pointer-events-auto'
    );

    // Ограничение ширины для fixed
    $fixedCardWidth = 'max-w-64 lg:max-w-80';

    // Если доступен только один пост — растягиваем на 2 колонки (только flow)
    $isAlone = (!$prev || !$next);
    $spanClass = ($mode === 'flow' && $isAlone) ? 'md:col-span-2' : '';
@endphp

    {{-- ================================================================ --}}
    {{-- FLOW РЕЖИМ (в потоке страницы) --}}
    {{-- ================================================================ --}}
    @if ($mode === 'flow')
        <nav 
            class="{{ $wrapperClasses }}" 
            role="navigation" 
            aria-label="{{ __('Post navigation', 'weblegko') }}"
        >
            {{-- ПРЕДЫДУЩАЯ СТАТЬЯ --}}
            @if ($prev)
                <a 
                    href="{{ $prev['url'] }}" 
                    rel="prev"
                    class="group flex flex-1 items-center min-w-0 {{ $spanClass }} {{ $flowCardClasses }}"
                    aria-label="{{ __('Previous article:', 'weblegko') }} {{ $prev['title'] }}"
                >
                    {{-- Стрелка --}}
                    <div class="hidden sm:block flex-shrink-0 text-gray-400 group-hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </div>

                    {{-- Thumbnail --}}
                    @if ($showThumb && $prev['thumb'])
                        <img 
                            src="{{ $prev['thumb'] }}" 
                            alt=""
                            loading="lazy"
                            class="{{ ($showExcerpt && $prev['excerpt']) ? 'w-16 h-16' : 'w-12 h-12' }} rounded-lg object-cover flex-shrink-0"
                        >
                    @endif

                    {{-- Текст --}}              
                    <div class="flex-1 min-w-0 flex flex-col gap-1">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            {{ __('Previous article', 'weblegko') }} 
                        </span>
                        
                        @if ($showTitle)
                            <span class="font-semibold text-gray-900 dark:text-white line-clamp-1 truncate group-hover:text-blue-600 transition-colors">
                                {{ $prev['title'] }}
                            </span>
                        @endif
                        
                        {{-- МАГИЯ FIX: Исправлена опечатка (было $next['excerpt']) --}}
                        @if ($showExcerpt && $prev['excerpt'])
                            <span class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1 truncate">
                                {{ $prev['excerpt'] }}
                            </span>
                        @endif
                    </div>
                </a>
            @endif

            {{-- СЛЕДУЮЩАЯ СТАТЬЯ --}}
            @if ($next)
                <a 
                    href="{{ $next['url'] }}" 
                    rel="next"
                    class="group flex flex-1 items-center min-w-0 flex-row-reverse text-right {{ $spanClass }} {{ $flowCardClasses }}"
                    aria-label="{{ __('Next article:', 'weblegko') }} {{ $next['title'] }}"
                >
                    {{-- Стрелка --}}
                    <div class="hidden sm:block flex-shrink-0 text-gray-400 group-hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>

                    {{-- Thumbnail --}}
                    @if ($showThumb && $next['thumb'])
                        <img 
                            src="{{ $next['thumb'] }}" 
                            alt=""
                            loading="lazy"
                            class="{{ ($showExcerpt && $next['excerpt']) ? 'w-16 h-16' : 'w-12 h-12' }} rounded-lg object-cover flex-shrink-0"
                        >
                    @endif
                  
                    {{-- Текст --}}
                    <div class="flex-1 min-w-0 flex flex-col gap-1">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            {{ __('Next article', 'weblegko') }}
                        </span>
                        
                        @if ($showTitle)
                            <span class="font-semibold text-gray-900 dark:text-white line-clamp-1 truncate group-hover:text-blue-600 transition-colors">
                                {{ $next['title'] }}
                            </span>
                        @endif
                        
                        @if ($showExcerpt && $next['excerpt'])
                            <span class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1 truncate">
                                {{ $next['excerpt'] }}
                            </span>
                        @endif
                    </div>
                </a>
            @endif      
        </nav>

    {{-- ================================================================ --}}
    {{-- FIXED РЕЖИМ (плавающая навигация по бокам) --}}
    {{-- ================================================================ --}}
    @elseif ($mode === 'fixed')

        {{-- 
            МАГИЯ: 
            1. leftOpen/rightOpen хранят состояние плашек.
            2. lastOpened помнит, кто открылся последним (для z-index).
            3. @click.self закрывает обе плашки при клике на пустой фон.
            4. :class включает pointer-events-auto только если хоть одна открыта, 
               чтобы не блокировать клики по сайту, когда они закрыты.
        --}}
        <nav 
            x-data="{ leftOpen: false, rightOpen: false, lastOpened: null }"
            @click.self="leftOpen = false; rightOpen = false"
            class="flex fixed inset-0 z-40 items-center justify-between"
            :class="(leftOpen || rightOpen) ? 'pointer-events-auto' : 'pointer-events-none'"
        >

        {{-- ПРЕДЫДУЩАЯ СТАТЬЯ (Слева) --}}
        @if ($prev)
            <div 
                x-cloak
                class="absolute left-0 top-1/2 -translate-y-1/2 group transition-all duration-300"
                {{-- МАГИЯ Z-INDEX: Повышаем z-index только если открыта последней --}}
                :class="leftOpen && lastOpened === 'left' ? 'z-50' : 'z-40'"
            >
                {{-- Контейнер плашки --}}
                <div 
                    class="{{ $fixedCardClasses }} flex items-center rounded-r-xl border-l-0 transition-all duration-300 ease-out"
                    :class="leftOpen ? 'translate-x-0' : '-translate-x-[calc(100%-2rem)] md:-translate-x-[calc(100%-3.5rem)] md:group-hover:translate-x-0'"
                >
                    {{-- Контент (ссылка) --}}
                    <div class="{{ $fixedCardWidth }} flex items-center gap-3 opacity-0 md:group-hover:opacity-100 transition-opacity duration-200 ease-out"
                         :class="leftOpen ? '!opacity-100' : ''"
                    >
                        <a 
                            href="{{ $prev['url'] }}" 
                            rel="prev"
                            class="flex items-center gap-3 flex-1 min-w-0 hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900 rounded-lg"
                            aria-label="{{ __('Previous article:', 'weblegko') }} {{ $prev['title'] }}"
                        >
                            @if ($showThumb && $prev['thumb'])
                                <img 
                                    src="{{ $prev['thumb'] }}" 
                                    alt=""
                                    loading="lazy"
                                    class="{{ ($showExcerpt && $prev['excerpt']) ? 'w-16 h-16' : 'w-12 h-12' }} rounded-lg object-cover flex-shrink-0"
                                >
                            @endif
                            <div class="flex-1 min-w-0 text-left whitespace-nowrap flex flex-col gap-1">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('Previous article', 'weblegko') }}
                                </span>
                                @if ($showTitle)
                                    <span class="font-semibold text-gray-900 dark:text-white truncate group-hover:text-blue-600 transition-colors">
                                        {{ $prev['title'] }}
                                    </span>
                                @endif
                                @if ($showExcerpt && $prev['excerpt'])
                                    <span class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1 truncate">
                                        {{ $prev['excerpt'] }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    </div>

                    {{-- Стрелка (кнопка для выезда) --}}
                    <button 
                        type="button"
                        {{-- МАГИЯ: При клике меняем состояние и записываем, что левая открылась последней --}}
                        @click="leftOpen = !leftOpen; if(leftOpen) lastOpened = 'left'"
                        class="flex-shrink-0 text-gray-400 hover:text-blue-600 transition-colors w-6 md:w-8 h-8 flex items-center justify-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900 rounded-md"
                        aria-label="{{ __('Show previous article', 'weblegko') }}"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- СЛЕДУЮЩАЯ СТАТЬЯ (Справа) --}}
        @if ($next)
            <div 
                x-cloak
                class="absolute right-0 top-1/2 -translate-y-1/2 group transition-all duration-300"
                {{-- МАГИЯ Z-INDEX: Повышаем z-index только если открыта последней --}}
                :class="rightOpen && lastOpened === 'right' ? 'z-50' : 'z-40'"
            >
                {{-- Контейнер плашки --}}
                <div 
                    class="{{ $fixedCardClasses }} flex items-center rounded-l-xl border-r-0 transition-all duration-300 ease-out"
                    :class="rightOpen ? 'translate-x-0' : 'translate-x-[calc(100%-2rem)] md:translate-x-[calc(100%-3.5rem)] md:group-hover:translate-x-0'"
                >
                    {{-- Стрелка (кнопка для выезда) --}}
                    <button 
                        type="button"
                        {{-- МАГИЯ: При клике меняем состояние и записываем, что правая открылась последней --}}
                        @click="rightOpen = !rightOpen; if(rightOpen) lastOpened = 'right'"
                        class="flex-shrink-0 text-gray-400 hover:text-blue-600 transition-colors w-6 md:w-8 h-8 flex items-center justify-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900 rounded-md"
                        aria-label="{{ __('Show next article', 'weblegko') }}"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    {{-- Контент (ссылка) --}}
                    <div class="{{ $fixedCardWidth }} flex items-center gap-3 opacity-0 md:group-hover:opacity-100 transition-opacity duration-200 ease-out"
                         :class="rightOpen ? '!opacity-100' : ''"
                    >
                        <a 
                            href="{{ $next['url'] }}" 
                            rel="next"
                            class="flex items-center gap-3 flex-1 min-w-0 hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900 rounded-lg"
                            aria-label="{{ __('Next article:', 'weblegko') }} {{ $next['title'] }}"
                        >
                            <div class="flex-1 min-w-0 text-right whitespace-nowrap flex flex-col gap-1">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('Next article', 'weblegko') }}
                                </span>
                                @if ($showTitle)
                                    <span class="font-semibold text-gray-900 dark:text-white truncate group-hover:text-blue-600 transition-colors">
                                        {{ $next['title'] }}
                                    </span>
                                @endif
                                @if ($showExcerpt && $next['excerpt'])
                                    <span class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1 truncate">
                                        {{ $next['excerpt'] }}
                                    </span>
                                @endif
                            </div>
                            @if ($showThumb && $next['thumb'])
                                <img 
                                    src="{{ $next['thumb'] }}" 
                                    alt=""
                                    loading="lazy"
                                    class="{{ ($showExcerpt && $next['excerpt']) ? 'w-16 h-16' : 'w-12 h-12' }} rounded-lg object-cover flex-shrink-0"
                                >
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        @endif

        </nav>
    @endif