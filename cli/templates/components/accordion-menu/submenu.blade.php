{{--
  ============================================================
  Компонент: Accordion Menu Submenu
  Описание: Раскрывающийся пункт меню с вложенностью.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - label   : string – текст пункта (ОБЯЗАТЕЛЬНО)
    - open    : bool   – открыто по умолчанию (по умолчанию false)
    - class   : string – дополнительные CSS-классы

  ============================================================
  СЛОТЫ (slots)
  ============================================================
    - default – вложенные пункты меню
    - icon    – кастомная иконка (SVG)

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Простое подменю:
    <x-accordion-menu.submenu label="Каталог">
        <x-accordion-menu.item href="/cat1">Категория 1</x-accordion-menu.item>
    </x-accordion-menu.submenu>

  2. Открытое по умолчанию:
    <x-accordion-menu.submenu label="Каталог" :open="true">
        <x-accordion-menu.item href="/cat1">Категория 1</x-accordion-menu.item>
    </x-accordion-menu.submenu>

  3. Многоуровневая вложенность:
    <x-accordion-menu.submenu label="Каталог">
        <x-accordion-menu.item href="/cat1">Категория 1</x-accordion-menu.item>
        
        <x-accordion-menu.submenu label="Подкаталог">
            <x-accordion-menu.item href="/sub1">Подкатегория 1</x-accordion-menu.item>
        </x-accordion-menu.submenu>
    </x-accordion-menu.submenu>
--}}

@props([
    'label' => '',
    'open'  => false,
    'class' => null,
])

@aware([
    'hover'          => false,
    'compact'        => false,
    'hoverHighlight' => true,
])

@php
    $padding = $compact ? 'px-2.5 py-1.5' : 'px-3 py-2.5';
    $fontSize = $compact ? 'text-xs' : 'text-sm';
    
    // Design System: Формируем классы ховера
    $hoverClasses = $hoverHighlight 
        ? 'hover:bg-accent hover:text-accent-foreground' 
        : 'hover:text-foreground';
    
    $buttonClasses = cn(
        'flex items-center gap-3 w-full rounded-md font-medium text-foreground transition-colors',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background',
        $padding,
        $fontSize,
        $hoverClasses
    );
@endphp

<div
    x-data="accordionSubmenu({{ json_encode(['open' => $open, 'label' => $label]) }})"
    @if($hover) 
        @mouseenter="open()" 
        @mouseleave="close()" 
    @endif
    class="{{ cn($class) }}"
>
    <button
        type="button"
         @if(!$hover) 
            @click="toggle()" 
        @endif
        x-bind:aria-expanded="isOpen"
        class="{{ $buttonClasses }}"
    >
        @isset($icon)
            <span class="flex-shrink-0 w-5 h-5 flex items-center justify-center">
                {{ $icon }}
            </span>
        @endisset
        
        <span class="flex-1 text-left">{{ $label }}</span>
        
        <svg
            class="w-4 h-4 flex-shrink-0 transition-transform duration-300 ease-in-out"
            x-bind:class="isOpen ? 'rotate-180' : ''"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="isOpen"
        x-collapse
        style="display: none;"
        class="pl-4 mt-1 space-y-1"
    >
        {{ $slot }}
    </div>
</div>