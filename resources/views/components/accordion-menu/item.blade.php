{{--
  ============================================================
  Компонент: Accordion Menu Item
  Описание: Пункт меню для accordion-menu.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - href   : string – ссылка (опционально)    
    - active : bool   – активный пункт (автоопределение через activeItem)
    - class  : string – дополнительные CSS-классы

  ============================================================
  СЛОТЫ (slots)
  ============================================================
    - default – текст пункта меню
    - icon    – кастомная иконка (SVG)

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Простой пункт:
    <x-accordion-menu.item href="/">Главная</x-accordion-menu.item>

  2. С кастомной иконкой (slot):
    <x-accordion-menu.item href="/">
        <x-slot:icon>
            <svg class="w-5 h-5">...</svg>
        </x-slot:icon>
        Главная
    </x-accordion-menu.item>

  3. Активный пункт (принудительно):
    <x-accordion-menu.item href="/" :active="true">Главная</x-accordion-menu.item>

  4. Без ссылки (кнопка):
    <x-accordion-menu.item @click="doSomething()">Действие</x-accordion-menu.item>
--}}

@props([
    'href'   => null,
    'active' => null,
    'class'  => null,
])

@aware([
    'activeItem'     => null,
    'compact'        => false,
    'hoverHighlight' => true,
])

@php
    $isActive = $active ?? ($href && $activeItem === $href);
    
    $padding = $compact ? 'px-2.5 py-1.5' : 'px-3 py-2.5';
    $fontSize = $compact ? 'text-xs' : 'text-sm';
    
    // Формируем классы ховера в зависимости от настроек родителя
    $hoverClasses = $hoverHighlight 
        ? 'hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' 
        : 'hover:text-gray-900 dark:hover:text-white'; // Без фона, только меняем текст
    
    $classes = cn([
        'flex items-center gap-3 rounded-md font-medium transition-colors',
        $padding,
        $fontSize,
        'text-gray-700 dark:text-gray-300' => !$isActive,
        $hoverClasses => !$isActive, // Применяем ховеры только к неактивным
        'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' => $isActive,
        $class,
    ]);
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $classes }}" {{ $attributes }}>
        @isset($icon)
            <span class="flex-shrink-0 w-5 h-5 flex items-center justify-center">
                {{ $icon }}
            </span>
        @endisset
        <span class="flex-1">{{ $slot }}</span>
    </a>
@else
    <button type="button" class="{{ $classes }} w-full text-left" {{ $attributes }}>
        @isset($icon)
            <span class="flex-shrink-0 w-5 h-5 flex items-center justify-center">
                {{ $icon }}
            </span>
        @endisset
        <span class="flex-1">{{ $slot }}</span>
    </button>
@endif