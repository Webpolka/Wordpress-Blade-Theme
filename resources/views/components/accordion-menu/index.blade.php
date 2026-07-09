{{--
  ============================================================
  Компонент: Accordion Menu
  Описание: Меню с вложенностью (аккордеон).
             Использует Alpine.js компонент 'accordionMenu'.
  ============================================================

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - activeItem     : string  – URL активного пункта для автоподсветки.
    - hover          : bool    – Открывать подменю при наведении (для десктопа). По умолчанию: false.
    - compact        : bool    – Компактный режим (меньше отступы и шрифт). По умолчанию: false.
    - hoverHighlight : bool    – Включить подсветку фона при наведении на пункт. По умолчанию: true.
    - class          : string  – Дополнительные CSS-классы для обертки <nav>.

  ------------------------------------------------------------
  ДОЧЕРНИЕ КОМПОНЕНТЫ
  ------------------------------------------------------------
    <x-accordion-menu.item>   : Обычный пункт меню (ссылка или кнопка)
    <x-accordion-menu.submenu>: Раскрывающийся пункт (аккордеон)

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. ПРОСТОЕ МЕНЮ (Без иконок):
    <x-accordion-menu>
        <x-accordion-menu.item href="/">Главная</x-accordion-menu.item>
        <x-accordion-menu.item href="/about">О нас</x-accordion-menu.item>
    </x-accordion-menu>

  2. С ИКОНКАМИ (Через слоты):
    <x-accordion-menu>
        <x-accordion-menu.item href="/">
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </x-slot:icon>
            Главная
        </x-accordion-menu.item>
    </x-accordion-menu>

  3. С ПОДМЕНЮ (Аккордеон):
    <x-accordion-menu>
        <x-accordion-menu.submenu label="Каталог">
            <x-slot:icon>
                <svg class="w-5 h-5"><!-- SVG иконки --></svg>
            </x-slot:icon>
            
            <x-accordion-menu.item href="/category1">Категория 1</x-accordion-menu.item>
        </x-accordion-menu.submenu>
    </x-accordion-menu>

  4. АКТИВНЫЙ ПУНКТ (Автоподсветка):
    <x-accordion-menu active-item="/about">
        <x-accordion-menu.item href="/about">О нас (Будет подсвечен)</x-accordion-menu.item>
    </x-accordion-menu>

  5. БЕЗ ПОДСВЕТКИ ФОНОМ ПРИ НАВЕДЕНИИ:
    <x-accordion-menu :hover-highlight="false">
        <x-accordion-menu.item href="/">Главная</x-accordion-menu.item>
    </x-accordion-menu>

  6. МЕНЮ С ОТКРЫТИЕМ ПО НАВЕДЕНИЮ (Hover mode):
    <x-accordion-menu :hover="true">
        <x-accordion-menu.submenu label="Каталог">
            <x-accordion-menu.item href="/cat1">Категория 1</x-accordion-menu.item>
        </x-accordion-menu.submenu>
    </x-accordion-menu>

  7. КОМПАКТНОЕ МЕНЮ (Compact mode):
    <x-accordion-menu :compact="true">
        <x-accordion-menu.submenu label="Настройки">
            <x-accordion-menu.item href="/set1">Профиль</x-accordion-menu.item>
        </x-accordion-menu.submenu>
    </x-accordion-menu>

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Требует подключенного Alpine.js и плагина @alpinejs/collapse.
    - Иконки поддерживаются ТОЛЬКО через слоты (x-slot:icon).
    - Плавная анимация раскрытия подменю работает "из коробки".
--}}

@props([
    'activeItem'     => null,
    'class'          => null,
    'hover'          => false,
    'compact'        => false,
    'hoverHighlight' => true,
])

<nav
    x-data="accordionMenu({{ json_encode(['activeItem' => $activeItem]) }})"
    class="flex flex-col gap-1 {{ $class }}"
>
    {{ $slot }}
</nav>