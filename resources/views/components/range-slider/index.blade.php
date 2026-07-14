{{--
  ============================================================
  Компонент: RangeSlider (Ползунок диапазона)
  Описание: Кастомный ползунок для форм (фильтры, настройки).
  ============================================================

  ------------------------------------------------------------
  ОСОБЕННОСТИ
  ------------------------------------------------------------
    • Два режима: Одиночный (value) и Диапазон (dual).
    • Отправляет данные через скрытые <input> (совместимость с WC).
    • 100% кастомизация вида (Tailwind + Design System).
    • Поддержка мыши и тач-экранов.

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - min      (int)    : Минимум. По умолчанию: 0.
    - max      (int)    : Максимум. По умолчанию: 100.
    - step     (int)    : Шаг. По умолчанию: 1.
    - dual     (bool)   : Режим двух бегунков (От/До). По умолчанию: false.
    - value    (int)    : Значение для одиночного режима.
    - minValue (int)    : Значение левого бегунка для dual режима.
    - maxValue (int)    : Значение правого бегунка для dual режима.
    - name     (string) : Имя для скрытого input (если dual, добавится _min и _max).

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Одиночный ползунок (например, процент скидки):
    <x-range-slider :min="0" :max="100" :value="20" name="discount" />

  2. Диапазон цен (Фильтр WooCommerce):
    <x-range-slider :min="0" :max="5000" :step="100" :dual="true" :min-value="500" :max-value="3000" name="price" />
--}}

@once
    <style>
        .wp-range-track::before,
        .wp-range-track::after {
            content: '';
            position: absolute;
            top: 0;
            height: 100%;
            width: var(--wp-range-pad, 1rem);
            background-color: rgb(var(--muted));
        }
        .wp-range-track::before {
            left: calc(var(--wp-range-pad, 1rem) * -1);
            border-top-left-radius: 9999px;
            border-bottom-left-radius: 9999px;
        }
        .wp-range-track::after {
            right: calc(var(--wp-range-pad, 1rem) * -1);
            border-top-right-radius: 9999px;
            border-bottom-right-radius: 9999px;
        }
    </style>
@endonce

@props([
    'min'          => 0,
    'max'          => 100,
    'step'         => 1,
    'dual'         => false,
    'value'        => null,
    'minValue'     => null,
    'maxValue'     => null,
    'name'         => 'range',
    'class'        => null,
    'thumbSize'    => 2.25,
    'showTooltips' => false, // Показывать тултипы над бегунками
    'showValues'   => false, // Показывать дефолтные значения под слайдером
])

@php
    $initValue = $value ?? $min;
    $initMin = $minValue ?? ($dual ? $min : $initValue);
    $initMax = $maxValue ?? ($dual ? $max : $initValue);
    $thumbPad = $thumbSize / 2;
@endphp

<div 
    x-data="rangeSlider({ 
        min: {{ $min }}, 
        max: {{ $max }}, 
        step: {{ $step }}, 
        dual: {{ $dual ? 'true' : 'false' }},
        minVal: {{ $initMin }},
        maxVal: {{ $initMax }}
    })"
    class="w-full pt-2 pb-4 {{ $class }}"
    style="padding-inline: {{ $thumbPad }}rem;"
>
    {{-- СЛОТ HEADER: Сюда можно вывести реактивные значения над слайдером --}}
    @isset($header)
        <div class="mb-4">
            {{ $header }}
        </div>
    @endisset

    <div class="relative w-full">
        {{-- Сам трек --}}
        <div 
            x-ref="track" 
            @mousedown="onTrackClick($event)" 
            @touchstart="onTrackClick($event)"
            class="wp-range-track relative w-full h-2 bg-muted cursor-pointer"
            style="--wp-range-pad: {{ $thumbPad }}rem;"
        >
            <div 
                class="absolute h-full rounded-full bg-primary"
                :style="`left: ${minPercent}%; right: ${100 - maxPercent}%`"
            ></div>
        </div>

               {{-- Бегунок 1 (Минимум) --}}
        @if ($dual)
        <div 
            @mousedown.prevent="startDrag('min')" 
            @touchstart.prevent="startDrag('min')"
            class="group absolute z-10 -translate-x-1/2 -translate-y-1/2 top-1/2 rounded-full bg-background border-2 border-primary shadow-sm cursor-grab focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background"
            :style="`left: ${minPercent}%; width: {{ $thumbSize }}rem; height: {{ $thumbSize }}rem;`"
            :class="{ 'cursor-grabbing': dragging === 'min', 'scale-110': dragging === 'min' }"
        >
            {{-- ВСТАВЛЯЕМ ТУЛТИП ВНУТРЬ БЕГУНКА --}}
            @if ($showTooltips)
                <x-tooltip-absolute position="top" distance="0.75">
                    <span x-text="minVal"></span>
                </x-tooltip-absolute>
            @endif
        </div>
        @endif

        {{-- Бегунок 2 (Максимум / Одиночный) --}}
        <div 
            @mousedown.prevent="startDrag('max')" 
            @touchstart.prevent="startDrag('max')"
            class="group absolute z-10 -translate-x-1/2 -translate-y-1/2 top-1/2 rounded-full bg-background border-2 border-primary shadow-sm cursor-grab focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background"            
            :style="`left: ${maxPercent}%; width: {{ $thumbSize }}rem; height: {{ $thumbSize }}rem;`"
            :class="{ 'cursor-grabbing': dragging === 'max', 'scale-110': dragging === 'max' }"
        >
            {{-- ВСТАВЛЯЕМ ТУЛТИП ВНУТРЬ БЕГУНКА --}}
            @if ($showTooltips)
                <x-tooltip-absolute position="top" distance="0.75">
                    <span x-text="maxVal"></span>
                </x-tooltip-absolute>
            @endif
        </div>
    </div>

    {{-- Скрытые инпуты для отправки в форме --}}
    @if ($dual)
        <input type="hidden" name="{{ $name }}_min" :value="minVal" />
        <input type="hidden" name="{{ $name }}_max" :value="maxVal" />
    @else
        <input type="hidden" name="{{ $name }}" :value="maxVal" />
    @endif

    {{-- Дефолтный вывод значений под слайдером (если включено) --}}
    @if ($showValues)
        @if ($dual)
            <div class="flex justify-between mt-4 text-sm text-muted-foreground">
                <span x-text="minVal"></span>
                <span x-text="maxVal"></span>
            </div>
        @else
            <div class="flex justify-end mt-4 text-sm text-muted-foreground">
                <span x-text="maxVal"></span>
            </div>
        @endif
    @endif
</div>