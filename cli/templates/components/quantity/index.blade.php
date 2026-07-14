{{--
  ============================================================
  Компонент: Quantity (Шагатор количества)
  Описание: Красивая замена стандартного input[type=number] 
             для WooCommerce и любых форм.
  ============================================================

  ------------------------------------------------------------
  ОСОБЕННОСТИ
  ------------------------------------------------------------
    • Идеально для WooCommerce (рендерит input name="quantity").
    • Поддержка дробного шага (step="0.5") для весовых товаров.
    • Валидация: не дает уйти ниже минимума или выше остатков на складе.
    • Управление с клавиатуры (стрелки вверх/вниз).
    • Design System (semantic colors).

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - value (int|float) : Начальное значение. По умолчанию: 1.
    - min   (int|float) : Минимум. По умолчанию: 1.
    - max   (int|float) : Максимум (остаток на складе). По умолчанию: 9999.
    - step  (int|float) : Шаг. По умолчанию: 1.
    - name  (string)    : Имя для input (WC требует 'quantity'). По умолчанию: 'quantity'.
    - size  (string)    : Размер ('sm', 'md', 'lg'). По умолчанию: 'md'.
    - class (string)    : Доп. классы для обертки.

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Стандартный вывод в карточке товара WooCommerce:
    <x-quantity :value="1" :min="1" :max="$product->get_stock_quantity()" />

  2. В корзине (маленький размер):
    <x-quantity size="sm" :value="$cart_item['quantity']" :max="$cart_item['data']->get_stock_quantity()" />

  3. Весовой товар (шаг 0.5):
    <x-quantity :step="0.5" :min="0.5" />
--}}

@props([
    'value' => 1,
    'min'   => 1,
    'max'   => 9999,
    'step'  => 1,
    'name'  => 'quantity',
    'size'  => 'md',
    'class' => null,
])

@php
    $sizes = [
        'sm' => ['btn' => 'w-8 h-8', 'input' => 'w-10 h-8 text-sm'],
        'md' => ['btn' => 'w-10 h-10', 'input' => 'w-12 h-10 text-base'],
        'lg' => ['btn' => 'w-12 h-12', 'input' => 'w-16 h-12 text-lg'],
    ];
    $currentSize = $sizes[$size] ?? $sizes['md'];

    $wrapperClasses = cn(
        'inline-flex items-center border border-input rounded-md overflow-hidden bg-background',
        $class
    );

    $btnClasses = cn(
        'flex items-center justify-center text-muted-foreground hover:bg-accent hover:text-accent-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-inset disabled:opacity-30 disabled:cursor-not-allowed disabled:pointer-events-none'
    );

    $inputClasses = cn(
        'text-center bg-transparent border-x border-input text-foreground font-medium outline-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none',
        $currentSize['input']
    );
@endphp

<div 
    x-data="quantity({ qty: {{ (float) $value }}, min: {{ (float) $min }}, max: {{ (float) ($max ?: 9999) }}, step: {{ (float) $step }} })"
    class="{{ $wrapperClasses }}"
>
    {{-- Кнопка Минус --}}
    <button 
        type="button" 
        @click="decrement()" 
        class="{{ $btnClasses }} {{ $currentSize['btn'] }}"
        :disabled="qty <= min"
        aria-label="{{ __('Decrease quantity', 'weblegko') }}"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4" /></svg>
    </button>

    {{-- Скрытый нативный инпут для WooCommerce --}}
    <input 
        type="hidden" 
        name="{{ $name }}" 
        :value="qty" 
    />

    {{-- Визуальный инпут (только для отображения и ручного ввода) --}}
    <input 
        type="number" 
        x-model="qty" 
        @change="update()" 
        @keydown.arrow-up.prevent="increment()" 
        @keydown.arrow-down.prevent="decrement()"
        :min="min" 
        :max="max" 
        :step="step" 
        class="{{ $inputClasses }}"
        aria-label="{{ __('Quantity', 'weblegko') }}"
    />

    {{-- Кнопка Плюс --}}
    <button 
        type="button" 
        @click="increment()" 
        class="{{ $btnClasses }} {{ $currentSize['btn'] }}"
        :disabled="qty >= max"
        aria-label="{{ __('Increase quantity', 'weblegko') }}"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
    </button>
</div>