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

    if (!$prevPost && !$nextPost) {
        return;
    }

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
        $wrapperClasses = 'flex fixed inset-0 z-40 pointer-events-none items-center justify-between';
    } else {
        $wrapperClasses = 'grid grid-cols-1 md:grid-cols-2 gap-3';
    }

    $wrapperClasses = cn($wrapperClasses, $class);

    // ========================================================================
    // Визуальные классы карточек (Design System)
    // ========================================================================
    $flowCardClasses = cn(
        'gap-5 p-2 sm:p-4 rounded-xl border',
        'border-border bg-card text-card-foreground', // Семантика!
        'shadow-sm transition-all duration-300',
        'hover:shadow-lg hover:-translate-y-1 hover:no-underline',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background'
    );

    $fixedCardClasses = cn(
        'gap-3 p-2 md:p-4 border',
        'border-border bg-card text-card-foreground', // Семантика!
        'shadow-lg transition-transform duration-300 ease-out hover:no-underline',
        'pointer-events-auto'
    );

    $fixedCardWidth = 'max-w-64 lg:max-w-80';
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
                    <div class="hidden sm:block flex-shrink-0 text-muted-foreground group-hover:text-primary transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </div>

                    @if ($showThumb && $prev['thumb'])
                        <img src="{{ $prev['thumb'] }}" alt="" loading="lazy" class="{{ ($showExcerpt && $prev['excerpt']) ? 'w-16 h-16' : 'w-12 h-12' }} rounded-lg object-cover flex-shrink-0">
                    @endif

                    <div class="flex-1 min-w-0 flex flex-col gap-1">
                        <span class="text-xs font-medium text-muted-foreground">{{ __('Previous article', 'weblegko') }}</span>
                        @if ($showTitle)
                            <span class="font-semibold text-card-foreground line-clamp-1 truncate group-hover:text-primary transition-colors">{{ $prev['title'] }}</span>
                        @endif
                        @if ($showExcerpt && $prev['excerpt'])
                            <span class="text-sm text-muted-foreground line-clamp-1 truncate">{{ $prev['excerpt'] }}</span>
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
                    <div class="hidden sm:block flex-shrink-0 text-muted-foreground group-hover:text-primary transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>

                    @if ($showThumb && $next['thumb'])
                        <img src="{{ $next['thumb'] }}" alt="" loading="lazy" class="{{ ($showExcerpt && $next['excerpt']) ? 'w-16 h-16' : 'w-12 h-12' }} rounded-lg object-cover flex-shrink-0">
                    @endif
                  
                    <div class="flex-1 min-w-0 flex flex-col gap-1">
                        <span class="text-xs font-medium text-muted-foreground">{{ __('Next article', 'weblegko') }}</span>
                        @if ($showTitle)
                            <span class="font-semibold text-card-foreground line-clamp-1 truncate group-hover:text-primary transition-colors">{{ $next['title'] }}</span>
                        @endif
                        @if ($showExcerpt && $next['excerpt'])
                            <span class="text-sm text-muted-foreground line-clamp-1 truncate">{{ $next['excerpt'] }}</span>
                        @endif
                    </div>
                </a>
            @endif      
        </nav>

    {{-- ================================================================ --}}
    {{-- FIXED РЕЖИМ (плавающая навигация по бокам) --}}
    {{-- ================================================================ --}}
    @elseif ($mode === 'fixed')
        <nav 
            x-data="{ leftOpen: false, rightOpen: false, lastOpened: null }"
            @click.self="leftOpen = false; rightOpen = false"
            class="flex fixed inset-0 z-40 items-center justify-between"
            :class="(leftOpen || rightOpen) ? 'pointer-events-auto' : 'pointer-events-none'"
        >

        {{-- ПРЕДЫДУЩАЯ СТАТЬЯ (Слева) --}}
        @if ($prev)
            <div x-cloak class="absolute left-0 top-1/2 -translate-y-1/2 group transition-all duration-300" :class="leftOpen && lastOpened === 'left' ? 'z-50' : 'z-40'">
                <div class="{{ $fixedCardClasses }} flex items-center rounded-r-xl border-l-0 transition-all duration-300 ease-out" :class="leftOpen ? 'translate-x-0' : '-translate-x-[calc(100%-2rem)] md:-translate-x-[calc(100%-3.5rem)] md:group-hover:translate-x-0'">
                    <div class="{{ $fixedCardWidth }} flex items-center gap-3 opacity-0 md:group-hover:opacity-100 transition-opacity duration-200 ease-out" :class="leftOpen ? '!opacity-100' : ''">
                        <a href="{{ $prev['url'] }}" rel="prev" class="flex items-center gap-3 flex-1 min-w-0 hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background rounded-lg" aria-label="{{ __('Previous article:', 'weblegko') }} {{ $prev['title'] }}">
                            @if ($showThumb && $prev['thumb'])
                                <img src="{{ $prev['thumb'] }}" alt="" loading="lazy" class="{{ ($showExcerpt && $prev['excerpt']) ? 'w-16 h-16' : 'w-12 h-12' }} rounded-lg object-cover flex-shrink-0">
                            @endif
                            <div class="flex-1 min-w-0 text-left whitespace-nowrap flex flex-col gap-1">
                                <span class="text-xs font-medium text-muted-foreground">{{ __('Previous article', 'weblegko') }}</span>
                                @if ($showTitle)
                                    <span class="font-semibold text-card-foreground truncate group-hover:text-primary transition-colors">{{ $prev['title'] }}</span>
                                @endif
                                @if ($showExcerpt && $prev['excerpt'])
                                    <span class="text-sm text-muted-foreground line-clamp-1 truncate">{{ $prev['excerpt'] }}</span>
                                @endif
                            </div>
                        </a>
                    </div>

                    <button type="button" @click="leftOpen = !leftOpen; if(leftOpen) lastOpened = 'left'" class="flex-shrink-0 text-muted-foreground hover:text-primary transition-colors w-6 md:w-8 h-8 flex items-center justify-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background rounded-md" aria-label="{{ __('Show previous article', 'weblegko') }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- СЛЕДУЮЩАЯ СТАТЬЯ (Справа) --}}
        @if ($next)
            <div x-cloak class="absolute right-0 top-1/2 -translate-y-1/2 group transition-all duration-300" :class="rightOpen && lastOpened === 'right' ? 'z-50' : 'z-40'">
                <div class="{{ $fixedCardClasses }} flex items-center rounded-l-xl border-r-0 transition-all duration-300 ease-out" :class="rightOpen ? 'translate-x-0' : 'translate-x-[calc(100%-2rem)] md:translate-x-[calc(100%-3.5rem)] md:group-hover:translate-x-0'">
                    <button type="button" @click="rightOpen = !rightOpen; if(rightOpen) lastOpened = 'right'" class="flex-shrink-0 text-muted-foreground hover:text-primary transition-colors w-6 md:w-8 h-8 flex items-center justify-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background rounded-md" aria-label="{{ __('Show next article', 'weblegko') }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>

                    <div class="{{ $fixedCardWidth }} flex items-center gap-3 opacity-0 md:group-hover:opacity-100 transition-opacity duration-200 ease-out" :class="rightOpen ? '!opacity-100' : ''">
                        <a href="{{ $next['url'] }}" rel="next" class="flex items-center gap-3 flex-1 min-w-0 hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background rounded-lg" aria-label="{{ __('Next article:', 'weblegko') }} {{ $next['title'] }}">
                            <div class="flex-1 min-w-0 text-right whitespace-nowrap flex flex-col gap-1">
                                <span class="text-xs font-medium text-muted-foreground">{{ __('Next article', 'weblegko') }}</span>
                                @if ($showTitle)
                                    <span class="font-semibold text-card-foreground truncate group-hover:text-primary transition-colors">{{ $next['title'] }}</span>
                                @endif
                                @if ($showExcerpt && $next['excerpt'])
                                    <span class="text-sm text-muted-foreground line-clamp-1 truncate">{{ $next['excerpt'] }}</span>
                                @endif
                            </div>
                            @if ($showThumb && $next['thumb'])
                                <img src="{{ $next['thumb'] }}" alt="" loading="lazy" class="{{ ($showExcerpt && $next['excerpt']) ? 'w-16 h-16' : 'w-12 h-12' }} rounded-lg object-cover flex-shrink-0">
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        @endif

        </nav>
    @endif