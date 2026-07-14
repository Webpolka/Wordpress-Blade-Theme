{{--
  Ok !

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
  | добавьь в массив  $componentHelpers в файле app/components.php
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
    // Design System: Семантические классы для списков
    $listClasses = $isTopLevel ? 'flex space-x-4' : 'bg-popover text-popover-foreground shadow-lg rounded-md border border-border';
    $itemClasses = $isTopLevel ? '' : 'border-b border-border last:border-0';
@endphp

<ul {{ $attributes->merge(['class' => $listClasses]) }}>
    @foreach ($items as $item)
        @php $hasChildren = !empty($item['children']); @endphp

        <li class="relative scrollbar-none {{ $itemClasses }}" x-data="dropdown({
            level: {{ $level }},
            delay: {{ $delay }},
            hasChildren: {{ $hasChildren ? 'true' : 'false' }}
        })" @mouseenter="onMouseEnter()"
            @mouseleave="scheduleClose()">

            <div class="dropdown-li-full text-foreground flex items-center justify-between gap-2 px-4 py-2 hover:bg-accent hover:text-accent-foreground transition cursor-pointer focus-within:ring-2 focus-within:ring-ring focus-within:ring-offset-2 focus-within:ring-offset-background"
                @if ($level > 1) @click.stop="toggleOpen($event)" @endif>
                <a class="leading-[1.2] focus-visible:outline-none" href="{{ $item['url'] ?? '#' }}">
                    <span>{{ $item['label'] }}</span>
                </a>

                @if ($hasChildren)
                    <svg class="inline-block w-4 h-4 shrink-0 transition-transform duration-200 text-muted-foreground"
                        :class="{ 'rotate-180': ({{ $level }} <= 1 && hover) || isOpen }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                @endif
            </div>

            @if ($hasChildren)
                <div x-show="{{ $level <= 1 ? 'hover' : 'isOpen' }}" x-cloak                    
                    @if ($level <= 1) @mouseenter="clearTimeout(closeTimeout)" @mouseleave="scheduleClose()" @endif
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="{{ $level > 1 ? 'pl-4' : '' }} w-48 bg-popover text-popover-foreground shadow-lg rounded-md z-50 max-h-[calc(100vh-4rem)] scrollbar-none overflow-y-auto border border-border"
                    :class="{
                        'absolute left-0 top-full': {{ $isTopLevel ? 'true' : 'false' }},
                        'fixed': {{ $level }} === 1,
                        'relative': {{ $level }} > 1,
                    }"
                    :style="{{ $level }} === 1 ? fixedPos : { maxHeight: 'calc(100vh - 4rem)' }"
                >
                    <x-dropdown-menu :items="$item['children']" level="{{ $level + 1 }}" :delay="$delay" />
                </div>
            @endif
        </li>
    @endforeach
</ul>