{{--
  Компонент: Mobile Drawer
  Описание: Выдвижное меню/панель с анимированным бургером.
             Использует Alpine.js компонент 'drawer'.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - position      : string – сторона выезда: 'left' (по умолчанию) | 'right'
    - width         : string – Tailwind-класс ширины (по умолчанию 'w-80')
    - overlay       : bool – показывать затемнение (по умолчанию true)
    - title         : string – заголовок панели (опционально)
    - hideOnDesktop : bool – скрывать на экранах ≥ lg (по умолчанию true)
    - name          : string – имя для программного управления (опционально)

  ============================================================
  СЛОТЫ (slots)
  ============================================================
    - x-slot:trigger  – кастомная кнопка-триггер (опционально, по умолчанию бургер)
    - default         – содержимое панели
    - x-slot:footer   – блок внизу панели (опционально)

  ============================================================
  УПРАВЛЕНИЕ
  ============================================================
  1. Через клик на триггер:
    <x-mobile-drawer>...</x-mobile-drawer>

  2. Программно через события (если есть name):
    <button x-data @click="$dispatch('drawer-toggle', 'cart')">Корзина</button>
    <button x-data @click="$dispatch('drawer-open', 'cart')">Открыть</button>
    <button x-data @click="$dispatch('drawer-close', 'cart')">Закрыть</button>

  ============================================================
  СОБЫТИЯ
  ============================================================
    - drawer:opened – когда панель открылась
    - drawer:closed – когда панель закрылась

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Базовое мобильное меню:
    <x-mobile-drawer>
        <nav class="flex flex-col gap-2">
            <a href="/">Главная</a>
            <a href="/about">О нас</a>
        </nav>
    </x-mobile-drawer>

  2. Меню справа с заголовком и footer:
    <x-mobile-drawer position="right" title="Меню">
        <nav class="flex flex-col gap-1">
            <a href="/">Главная</a>
            <a href="/about">О нас</a>
        </nav>
        
        <x-slot:footer>
            <div class="flex gap-3">
                <a href="#" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-5 h-5"><use href="#icon-facebook"></use></svg>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-5 h-5"><use href="#icon-instagram"></use></svg>
                </a>
            </div>
        </x-slot:footer>
    </x-mobile-drawer>

  3. С кастомным триггером:
    <x-mobile-drawer position="right" title="Фильтры">
        <x-slot:trigger>
            <button class="p-2 text-gray-700 dark:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
            </button>
        </x-slot:trigger>
        
        <div class="space-y-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" class="rounded"> Категория 1
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" class="rounded"> Категория 2
            </label>
        </div>
        
        <x-slot:footer>
            <x-button variant="primary" class="w-full">Применить</x-button>
        </x-slot:footer>
    </x-mobile-drawer>

  4. Корзина с программным управлением:
    <button x-data @click="$dispatch('drawer-toggle', 'cart')" class="relative">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
    </button>
    
    <x-mobile-drawer name="cart" position="right" title="Корзина">
        <div class="space-y-4">
            <div class="flex gap-3 p-3 border rounded-lg">
                <img src="product.jpg" class="w-16 h-16 rounded object-cover">
                <div class="flex-1">
                    <h4 class="font-semibold">Товар 1</h4>
                    <p class="text-sm text-gray-500">Размер: M</p>
                    <p class="text-sm font-medium mt-1">1 000 ₽</p>
                </div>
            </div>
        </div>
        
        <x-slot:footer>
            <div class="flex justify-between items-center mb-3">
                <span class="font-semibold">Итого:</span>
                <span class="text-xl font-bold">3 000 ₽</span>
            </div>
            <x-button variant="primary" class="w-full">Оформить заказ</x-button>
        </x-slot:footer>
    </x-mobile-drawer>

  5. Показывать на всех экранах (боковая панель):
    <x-mobile-drawer :hideOnDesktop="false" position="left" width="w-72" title="Навигация">
        <nav class="flex flex-col gap-1">
            <a href="/" class="px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Главная</a>
            <a href="/about" class="px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">О нас</a>
        </nav>
    </x-mobile-drawer>

  ============================================================
  ACCESSIBILITY
  ============================================================
    - aria-expanded на триггере (отражает состояние)
    - aria-controls связывает триггер с панелью
    - role="dialog" + aria-modal на панели
    - Focus trap — Tab ходит только внутри drawer
    - Возврат фокуса к триггеру после закрытия
    - Escape закрывает drawer
    - aria-label на кнопках

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Использует Alpine компонент 'drawer'
    - Анимация бургера (три линии → крестик)
    - Focus trap для accessibility
    - Блокирует скролл body при открытии
    - Тёмная тема через dark: классы
--}}


@props([
    'position'      => 'left',
    'width'         => 'w-80',
    'overlay'       => true,
    'title'         => null,
    'hideOnDesktop' => true,
    'name'          => null,
])

@php
    $drawerId = $name ?? 'drawer-' . uniqid();
    $triggerId = "trigger-{$drawerId}";
    $panelId = "panel-{$drawerId}";
    
    $translateClass = $position === 'left' ? '-translate-x-full' : 'translate-x-full';
    $positionClass = $position === 'left' ? 'left-0' : 'right-0';
    $desktopClass = $hideOnDesktop ? 'lg:hidden' : '';

    // НОВОЕ: Переводы для Alpine JS
    $labelOpen = __('Open menu', 'weblegko');
    $labelClose = __('Close menu', 'weblegko');
@endphp

<div
    x-data="drawer({{ json_encode([
        'position' => $position,
        'width' => $width,
        'overlay' => $overlay,
        'title' => $title,
        'name' => $name,
    ]) }})"
    @keydown.escape.window="handleEscape()"
    @keydown.tab="handleTab($event)"
    id="{{ $drawerId }}"
    class="{{ $desktopClass }}"
>
    {{-- Триггер (кастомный слот) --}}
    @if (isset($trigger))
        <div 
            id="{{ $triggerId }}"
            data-drawer-trigger-element
            @click="toggle()"
            role="button"
            tabindex="0"
            x-bind:aria-expanded="isOpen"
            aria-controls="{{ $panelId }}"
            aria-label="{{ $labelOpen }}"
            @keydown.enter="toggle()"
            @keydown.space.prevent="toggle()"
            class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900 rounded-md"
        >
            {{ $trigger }}
        </div>
    @else
        {{-- Триггер (бургер по умолчанию) --}}
        <button
            id="{{ $triggerId }}"
            data-drawer-trigger-element
            type="button"
            @click="toggle()"
            x-bind:aria-expanded="isOpen"
            aria-controls="{{ $panelId }}"
            x-bind:aria-label="isOpen ? '{{ $labelClose }}' : '{{ $labelOpen }}'"
            class="p-2 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors rounded-md outline-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900"
        >
            <span class="sr-only" x-text="isOpen ? '{{ $labelClose }}' : '{{ $labelOpen }}'"></span>
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="4" y1="6" x2="20" y2="6"
                    x-bind:style="isOpen ? 'transform: translateY(18%) rotate(45deg)' : ''"
                    class="transition-all duration-300 ease-out origin-center" />
                <line x1="4" y1="12" x2="20" y2="12"
                    x-bind:style="isOpen ? 'opacity: 0' : 'opacity: 1'"
                    class="transition-opacity duration-300 ease-out" />
                <line x1="4" y1="18" x2="20" y2="18"
                    x-bind:style="isOpen ? 'transform: translateY(-18%) rotate(-45deg)' : ''"
                    class="transition-all duration-300 ease-out origin-center" />
            </svg>
        </button>
    @endif

    {{-- Overlay (затемнение) --}}
    @if ($overlay)
        <div
            x-show="isOpen"
            x-cloak
            x-transition:enter="transition-opacity duration-300 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-200 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="close()"
            class="fixed inset-0 z-40 bg-black/30 backdrop-blur-sm"
            aria-hidden="true"
        ></div>
    @endif

    {{-- Панель drawer --}}
    <div
        x-ref="panel"
        id="{{ $panelId }}"
        role="dialog"
        aria-modal="true"
        @if($title) aria-labelledby="{{ $panelId }}-title" @endif
        tabindex="-1"
        x-show="isOpen"
        x-cloak
        x-transition:enter="transition-transform duration-300 ease-out"
        x-transition:enter-start="{{ $translateClass }}"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-200 ease-in"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="{{ $translateClass }}"
        class="fixed inset-y-0 {{ $positionClass }} z-50 flex max-w-full outline-none"
    >
        <div class="relative {{ $width }} max-w-full bg-white dark:bg-slate-800 shadow-2xl h-full flex flex-col">
            {{-- Кнопка закрытия (крестик) --}}
            <button
                type="button"
                @click="close()"
                class="absolute top-3 right-3 p-2 text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 rounded-md transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-800 z-10"
                aria-label="{{ $labelClose }}"
            >
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Заголовок --}}
            @if ($title)
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 shrink-0">
                    <h3 id="{{ $panelId }}-title" class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                        {{ $title }}
                    </h3>
                </div>
            @endif

            {{-- Контент (прокручиваемый) --}}
            <div class="flex-1 overflow-y-auto px-6 py-4">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            @if (isset($footer))
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 shrink-0">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>