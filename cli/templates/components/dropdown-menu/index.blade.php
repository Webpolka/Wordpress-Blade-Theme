{{--
  |--------------------------------------------------------------------------
  | Компонент: Dropdown Menu
  |--------------------------------------------------------------------------
  | Десктопное многоуровневое меню с выпадающими подменю.
  |
  | Особенности:
  |   • Level 0 (top-level)  — горизонтальная навигация, hover-открытие
  |   • Level 1 (подменю)    — fixed-позиционирование, авто-флип влево/вправо
  |   • Level 2+ (вложенные) — relative-позиционирование, click-открытие
  |
  | Alpine-компонент регистрируется через Alpine.data('dropdown', ...).  
  |
  |--------------------------------------------------------------------------
  | Пропсы:
  |--------------------------------------------------------------------------
  |   items  (array) — массив пунктов меню. Структура:
  |                    [
  |                      'label'    => 'Пункт',
  |                      'url'      => '/path',
  |                      'children' => [...]  // опционально, рекурсивно
  |                    ]
  |   level  (int)   — текущий уровень вложенности (0 — корневой).
  |                    Передаётся автоматически при рекурсивном вызове.
  |   delay  (int)   — задержка закрытия в мс (hover-intent). По умолчанию 500.
  |
  |--------------------------------------------------------------------------
  | Подключи функцию -  build_tree_menu 
  |--------------------------------------------------------------------------
  |
  | сделай доступной функцию для создания дерева менюшек
  | добавьь в массив  $componentHelpers в app/setup.php
  |
  | get_theme_file_path('resources/views/components/dropdown-menu/build-tree-menu.php'),
  |
  |--------------------------------------------------------------------------
  | Пример использования в blade:
  |--------------------------------------------------------------------------
  |    @@php($primaryMenu = build_tree_menu('primary_navigation'))
  |    <x-dropdown-menu :items="$primaryMenu" />
  |
  |   где $primaryMenu — массив вида:
  |   [
  |     ['label' => 'Главная', 'url' => '/'],
  |     [
  |       'label' => 'Каталог',
  |       'url'   => '/catalog',
  |       'children' => [
  |         ['label' => 'Товары', 'url' => '/catalog/products'],
  |         [
  |           'label' => 'Категории',
  |           'url'   => '/catalog/categories',
  |           'children' => [
  |             ['label' => 'Электроника', 'url' => '/catalog/electronics'],
  |             ['label' => 'Одежда',      'url' => '/catalog/clothing'],
  |           ]
  |         ],
  |       ]
  |     ],
  |     ['label' => 'Контакты', 'url' => '/contacts'],
  |   ]
  |
  |--------------------------------------------------------------------------
  | Структура уровней:
  |--------------------------------------------------------------------------
  |   Level 0: <ul> с flex-пунктами. Открытие по hover.
  |            Меню открывается через CSS x-show="hover".
  |
  |   Level 1: <ul> внутри dropdown-пункта. Открытие по hover.
  |            Меню fixed, координаты считаются в JS (fixedPos).
  |            При открытии блокируется скролл body.
  |
  |   Level 2+: <ul> внутри dropdown-пункта. Открытие по клику.
  |             Меню relative, вложено в родительский контейнер.
  |             При открытии происходит авто-скролл к пункту.
  |
  |--------------------------------------------------------------------------
  | Ключевые селекторы (используются в JS):
  |--------------------------------------------------------------------------
  |   .dropdown-li-full  — триггер-блок (div с ссылкой и стрелкой).
  |                        Его координаты используются для позиционирования.
  |   :scope > div:last-child — контейнер подменю.
  |                        Его ширина измеряется для расчёта позиции.
  |   .overflow-y-auto   — ближайший скролл-контейнер.
  |                        Используется для авто-скролла при toggleOpen.
  |
  --}}

@props([
    'items' => [],
    'level' => 0,
    'delay' => 300,
])

@php
    $isTopLevel = $level === 0;
    // Стили корневого <ul> — горизонтальный flex
    // Стили вложенных <ul> — карточка с тенью
    $listClasses = $isTopLevel ? 'flex space-x-4' : 'bg-white dark:bg-gray-800 shadow-lg rounded-md';
    // Разделители между пунктами (только для вложенных)
    $itemClasses = $isTopLevel ? '' : 'border-b border-gray-100 dark:border-gray-700 last:border-0';
@endphp

<ul {{ $attributes->merge(['class' => $listClasses]) }}>
    @foreach ($items as $item)
        @php $hasChildren = !empty($item['children']); @endphp

        {{--
          <li> — контейнер пункта.
          x-data="dropdown(...)" — инициализация Alpine-компонента с конфигом.
          hover-события управляют открытием/закрытием.
        --}}
        <li class="relative scrollbar-none {{ $itemClasses }}" x-data="dropdown({
            level: {{ $level }},
            delay: {{ $delay }},
            hasChildren: {{ $hasChildren ? 'true' : 'false' }}
        })" @mouseenter="onMouseEnter()"
            @mouseleave="scheduleClose()">

            {{--
              Триггер-блок (.dropdown-li-full):
                • Содержит ссылку и стрелку (если есть дети)
                • На level > 1 клик переключает isOpen (toggleOpen)
                • Его координаты используются JS для позиционирования fixed-меню
            --}}
            <div class="dropdown-li-full text-black flex items-center justify-between gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition cursor-pointer"
                @if ($level > 1) @click.stop="toggleOpen($event)" @endif>
                <a class="leading-[1.2]" href="{{ $item['url'] ?? '#' }}">
                    <span>{{ $item['label'] }}</span>
                </a>

                {{-- Стрелка: вращается при hover (level ≤ 1) или при isOpen (level > 1) --}}
                @if ($hasChildren)
                    <svg class="inline-block w-4 h-4 shrink-0 transition-transform duration-200"
                        :class="{ 'rotate-180': ({{ $level }} <= 1 && hover) || isOpen }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                @endif
            </div>

            {{--
              Контейнер подменю.
              Режим отображения зависит от уровня:
                • Level ≤ 1: hover-режим (x-show="hover"), mouseenter/leave
                • Level > 1:  click-режим (x-show="isOpen"), без hover-обработчиков

              Позиционирование:
                • Level 0: absolute, под триггером (top-full left-0)
                • Level 1: fixed, координаты из fixedPos (JS-расчёт)
                • Level 2+: relative, в потоке родителя (pl-4 для отступа)
            --}}
            @if ($hasChildren)
                <div x-show="{{ $level <= 1 ? 'hover' : 'isOpen' }}" x-cloak                    
                    @if ($level <= 1) @mouseenter="clearTimeout(closeTimeout)" @mouseleave="scheduleClose()" @endif
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="{{ $level > 1 ? 'pl-4' : '' }} w-48 bg-white dark:bg-gray-800 shadow-lg rounded-md z-50 max-h-[calc(100vh-4rem)] scrollbar-none overflow-y-auto"
                    :class="{
                        'absolute left-0 top-full': {{ $isTopLevel ? 'true' : 'false' }},
                        'fixed': {{ $level }} === 1,
                        'relative': {{ $level }} > 1,
                    }"
                    :style="{
                        left: {{ $level }} === 1 ? fixedPos.left + 'rem' : 'auto',
                        top: {{ $level }} === 1 ? fixedPos.top + 'rem' : 'auto',
                        maxHeight: {{ $level }} === 1 ? fixedPos.maxHeight + 'rem' : 'calc(100vh - 4rem)'
                    }">
                    {{-- Рекурсивный вызов компонента для вложенных пунктов --}}
                    <x-dropdown-menu :items="$item['children']" level="{{ $level + 1 }}" :delay="$delay" />
                </div>
            @endif
        </li>
    @endforeach
</ul>
