{{--
  ============================================================
  Компонент: InlineTooltip (Дочерний абсолютный тултип)
  Описание: Вставляется ВНУТРЬ любого элемента (например, бегунка 
             слайдера). Не оборачивает его, не ломает верстку.
             Использует ту же логику и внешний вид, что и <x-tooltip>.
  ============================================================

  ------------------------------------------------------------
  КАК ИСПОЛЬЗОВАТЬ
  ------------------------------------------------------------
  1. Родитель должен иметь классы `group` и `relative` (или `absolute`).
  2. Вставь <x-inline-tooltip> внутрь родителя.
  3. Тултип появится при ховере (group-hover) или фокусе.

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - position (string) : 'top', 'bottom', 'left', 'right'. По умолчанию: 'top'.
    - arrow    (bool)   : Показывать стрелку. По умолчанию: true.
    - theme    (string) : 'dark' (по умолчанию) | 'light'.
    - color    (string) : Кастомные Tailwind классы (поддерживает градиенты!).
    - distance (int)    : Расстояние до родителя в rem. По умолчанию: 0.5.
    - class    (string) : Доп. классы.
--}}

@props([
    'position' => 'top',
    'delay' => 200,
    'arrow' => true,
    'theme' => 'light',
    'color'    => null,
    'distance' => 0.5,
    'text' => null,
    'class' => null,
])

{{-- 
  МАГИЯ: Используем тот же Alpine-компонент 'tooltip', чтобы получить 
  доступ к геттерам (positionClasses, themeClasses, arrowColorClasses, arrowBgStyle).
  Но мы НЕ вызываем show()/hide(), так как появление управляется через CSS group-hover.
--}}
<div x-data="tooltip({{ json_encode([
    'position' => $position,
    'delay' => $delay,
    'arrow' => $arrow,
    'theme' => $theme,
    'color'    => $color,
    'distance' => $distance,
]) }})"
    x-bind:class="positionClasses"
    x-bind:style="distanceStyle"    
    class="{{ cn('absolute z-50 text-xs font-medium rounded-md shadow-sm whitespace-nowrap pointer-events-none opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 group-focus-within:opacity-100 group-focus-within:scale-100 transition-all duration-150 ease-out', $class) }}"
>
    {{-- Контент --}}
    <div x-bind:class="themeClasses" class="px-3 py-1.5 rounded-md">
        {{ $slot }}
    </div>

    {{-- Стрелка (вычисляется через тот же Alpine-компонент) --}}
    @if ($arrow)
        <div x-bind:class="[arrowClasses, arrowColorClasses]" x-bind:style="arrowBgStyle" class="absolute w-2 h-2"></div>
    @endif
</div>