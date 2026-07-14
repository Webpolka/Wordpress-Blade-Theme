{{--
  ============================================================
  Компонент: StarRating (Звездный рейтинг)
  Описание: Вывод рейтинга (0-5) и интерактивный ввод для отзывов.
  ============================================================

  ------------------------------------------------------------
  ОСОБЕННОСТИ
  ------------------------------------------------------------
    • Два режима: Просмотр (read-only) и Ввод (interactive).
    • Поддержка дробных значений (4.5, 3.2 и т.д.) в режиме просмотра.
    • Работает на чистом Alpine.js (для ввода) и CSS (для просмотра).
    • Полная поддержка Design System (использует CSS-переменные --rating и --muted-foreground).
    • Защита от мигания (FOUC) при загрузке страницы.

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - value       (float)  : Текущий рейтинг (0 - 5). По умолчанию: 0.
    - max         (int)    : Максимальное кол-во звезд. По умолчанию: 5.
    - interactive (bool)   : Режим ввода (кликабельно). По умолчанию: false.
    - name        (string) : Имя для скрытого input (для форм). По умолчанию: 'rating'.
    - size        (string) : Размер ('sm', 'md', 'lg'). По умолчанию: 'md'.
    - gap         (string) : Расстояние между звездами (Tailwind классы). По умолчанию: 'gap-0.5'.
    - class       (string) : Доп. классы для обертки.

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Вывод рейтинга товара (с половинкой):
    <x-star-rating :value="4.5" />

  2. Вывод рейтинга с подписью (маленький, для карточки товара):
    <div class="flex items-center gap-1">
        <x-star-rating :value="3.7" size="sm" />
        <span class="text-xs text-muted-foreground">(3.7)</span>
    </div>

  3. Интерактивный ввод для отзыва (в форме):
    <form action="/reviews" method="POST">
        <x-star-rating :interactive="true" name="rating" :value="5" />
        <button type="submit">Отправить отзыв</button>
    </form>

  4. Прослушивание события выбора (Alpine.js):
    Если нужно вызвать JS-функцию при клике на звезду, слушай событие `rating-set`:
    <div x-data="{ showToast(rating) { alert('Вы поставили ' + rating); } }">
        <x-star-rating :interactive="true" @rating-set.window="showToast($event.detail)" />
    </div>

  5. Кастомизация отступов и размеров:
    <x-star-rating :value="5" size="lg" gap="gap-2" />
--}}

@props([
    'value'       => 0,
    'max'         => 5,
    'interactive' => false,
    'name'        => 'rating',
    'size'        => 'md',
    'class'       => null,
    'gap'         => 'gap-0.5',
])

@php
    $sizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ];
    $currentSize = $sizes[$size] ?? $sizes['md'];

    // Защита от значений больше максимума
    $value = min(max((float) $value, 0), (int) $max);
    
    // Процент заполнения для CSS слоя (от 0 до 100)
    $fillPercentage = ($value / $max) * 100;

    $wrapperClasses = cn('inline-flex items-center', $class);

    // SVG звезды (используем fill="currentColor", чтобы работали CSS-переменные)    
    $starSvg = '<svg class="' . $currentSize . ' shrink-0" fill="currentColor" viewBox="0 0 640 640"><path d="M341.5 45.1C337.4 37.1 329.1 32 320.1 32C311.1 32 302.8 37.1 298.7 45.1L225.1 189.3L65.2 214.7C56.3 216.1 48.9 222.4 46.1 231C43.3 239.6 45.6 249 51.9 255.4L166.3 369.9L141.1 529.8C139.7 538.7 143.4 547.7 150.7 553C158 558.3 167.6 559.1 175.7 555L320.1 481.6L464.4 555C472.4 559.1 482.1 558.3 489.4 553C496.7 547.7 500.4 538.8 499 529.8L473.7 369.9L588.1 255.4C594.5 249 596.7 239.6 593.9 231C591.1 222.4 583.8 216.1 574.8 214.7L415 189.3L341.5 45.1z"/></svg>';
@endphp

@if ($interactive)
    {{-- РЕЖИМ ВВОДА (Interactive) --}}
    <div 
        x-data="{ 
            rating: {{ $value }}, 
            hover: 0
        }" 
        class="{{ $gap }} {{ $wrapperClasses }}"
        @mouseleave="hover = 0"
    >
        {{-- Скрытый input для отправки в форме --}}
        <input type="hidden" name="{{ $name }}" :value="rating" />

        @for ($i = 1; $i <= $max; $i++)
            <button 
                type="button"
                @click="rating = {{ $i }}; $dispatch('rating-set', {{ $i }})"
                @mouseenter="hover = {{ $i }}"
                class="rounded-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background transition-colors"
                :aria-label="'Оценить на ' + {{ $i }} + ' из ' + {{ $max }}"
            >
                {{-- МАГИЯ FIX: Используем style и :style, чтобы избежать конфликта классов Tailwind --}}
                <span 
                    class="block transition-colors" 
                    style="{{ $value >= $i ? 'color: rgb(var(--rating))' : 'color: rgb(var(--muted-foreground))' }}"
                    :style="(hover >= {{ $i }} || rating >= {{ $i }}) ? 'color: rgb(var(--rating))' : 'color: rgb(var(--muted-foreground))'"
                >
                    {!! $starSvg !!}
                </span>
            </button>
        @endfor
    </div>
@else
    {{-- РЕЖИМ ПРОСМОТРА (Read-only) с CSS трюком для половинок --}}
    <div class="{{ $wrapperClasses }}" aria-label="Рейтинг: {{ $value }} из {{ $max }}">
        <div class="relative inline-flex">
            {{-- Слой 1: Пустые звезды (серые) --}}
            <div class="flex text-muted-foreground {{ $gap }}">
                @for ($i = 0; $i < $max; $i++)
                    {!! $starSvg !!}
                @endfor
            </div>

            {{-- Слой 2: Заполненные звезды (оранжевые), обрезанные по проценту --}}
            <div class="absolute inset-0 overflow-hidden text-rating" style="width: {{ $fillPercentage }}%;">
                <div class="flex w-max {{ $gap }}">
                    @for ($i = 0; $i < $max; $i++)
                        {!! $starSvg !!}
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endif