{{--
  Ok !  

  Компонент: Checkbox Pill
  Описание: Овальный checkbox с текстом внутри. Без иконки.
  Верстальщик сам оборачивает компонент в <label>.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - name     : string – имя поля (обязательно для отправки формы)
    - id       : string – id (генерируется уникально, если не передан)
    - value    : mixed – значение checkbox (по умолчанию '1')
    - checked  : mixed – начальное состояние:
                          - bool (true/false) для одиночного
                          - array для группы checkbox'ов
    - required : bool – обязательно поле (по умолчанию false)
    - disabled : bool – отключено (по умолчанию false)
    - error    : string – текст серверной ошибки
    - color    : string – цвет при checked:
                          'blue' (по умолчанию), 'green', 'red',
                          'purple', 'orange', 'pink', 'gray'
    - size     : string – размер: 'sm' | 'md' (по умолчанию) | 'lg'
    - class    : string – дополнительные CSS-классы

  ============================================================
  СЛОТ (slot)
  ============================================================
    Внутрь компонента передаётся текст (или любой контент).

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1 Базовый
    <label class="cursor-pointer">
        <x-checkbox-pill name="agree">Я согласен</x-checkbox-pill>
    </label>

  2 С предзаполнением
    <label class="cursor-pointer">
        <x-checkbox-pill name="subscribe" :checked="true">
            Подписаться
        </x-checkbox-pill>
    </label>

  3 Группа pill'ов (фильтры)
    <div class="flex flex-wrap gap-2">
        <label class="cursor-pointer">
            <x-checkbox-pill name="tags[]" value="laravel" :checked="['laravel']" color="red">
                Laravel
            </x-checkbox-pill>
        </label>
        <label class="cursor-pointer">
            <x-checkbox-pill name="tags[]" value="vue" :checked="['laravel']" color="green">
                Vue.js
            </x-checkbox-pill>
        </label>
        <label class="cursor-pointer">
            <x-checkbox-pill name="tags[]" value="tailwind" :checked="['laravel']" color="blue">
                Tailwind
            </x-checkbox-pill>
        </label>
        <label class="cursor-pointer">
            <x-checkbox-pill name="tags[]" value="alpine" :checked="['laravel']" color="purple">
                Alpine.js
            </x-checkbox-pill>
        </label>
    </div>

  4 С разными цветами
    <div class="flex flex-wrap gap-2">
        <label class="cursor-pointer">
            <x-checkbox-pill name="c1" color="blue">Синий</x-checkbox-pill>
        </label>
        <label class="cursor-pointer">
            <x-checkbox-pill name="c2" color="green">Зелёный</x-checkbox-pill>
        </label>
        <label class="cursor-pointer">
            <x-checkbox-pill name="c3" color="red">Красный</x-checkbox-pill>
        </label>
        <label class="cursor-pointer">
            <x-checkbox-pill name="c4" color="purple">Пурпурный</x-checkbox-pill>
        </label>
        <label class="cursor-pointer">
            <x-checkbox-pill name="c5" color="orange">Оранжевый</x-checkbox-pill>
        </label>
        <label class="cursor-pointer">
            <x-checkbox-pill name="c6" color="pink">Розовый</x-checkbox-pill>
        </label>
        <label class="cursor-pointer">
            <x-checkbox-pill name="c7" color="gray">Серый</x-checkbox-pill>
        </label>
    </div>

  5 С разными размерами
    <div class="flex items-center gap-2">
        <label class="cursor-pointer">
            <x-checkbox-pill name="s1" size="sm">Маленький</x-checkbox-pill>
        </label>
        <label class="cursor-pointer">
            <x-checkbox-pill name="s2" size="md">Средний</x-checkbox-pill>
        </label>
        <label class="cursor-pointer">
            <x-checkbox-pill name="s3" size="lg">Большой</x-checkbox-pill>
        </label>
    </div>

  6 С иконкой внутри (через slot)
    <label class="cursor-pointer">
        <x-checkbox-pill name="notify" color="purple">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Уведомления
            </span>
        </x-checkbox-pill>
    </label>

  7 Отключённый
    <label class="cursor-not-allowed">
        <x-checkbox-pill name="disabled" :checked="true" disabled>
            Недоступно
        </x-checkbox-pill>
    </label>

  8 С серверной ошибкой (Laravel)
    <label class="cursor-pointer">
        <x-checkbox-pill
            name="terms"
            error="{{ isset($errors) ? $errors->first('terms') : '' }}"
        >
            Принимаю условия
        </x-checkbox-pill>
    </label>

  9 С восстановлением после ошибки валидации
    <label class="cursor-pointer">
        <x-checkbox-pill
            name="agree"
            :checked="old('agree') ? true : false"
        >
            Я согласен
        </x-checkbox-pill>
    </label>

  10 Использование как теги-фильтры
    <div class="flex flex-wrap gap-2">
        @foreach(['PHP', 'JavaScript', 'Python', 'Go', 'Rust'] as $lang)
            <label class="cursor-pointer">
                <x-checkbox-pill
                    name="languages[]"
                    :value="strtolower($lang)"
                    :checked="old('languages', [])"
                >
                    {{ $lang }}
                </x-checkbox-pill>
            </label>
        @endforeach
    </div>

  ============================================================
  КАК ИСПОЛЬЗОВАТЬ
  ============================================================
    1. Верстальщик оборачивает <x-checkbox-pill> в <label>.
    2. Текст (или любой контент) передаётся через slot.
    3. Клик по label автоматически кликает скрытый input (нативное поведение).
    4. При checked: фон и текст меняют цвет.

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Компонент НЕ содержит label — верстальщик сам оборачивает.
    - Автоматически определяется одиночный режим (bool) или групповой (array)
      по типу значения checked.
    - Для группы используйте name с [] (например, "tags[]").
    - Поддерживается тёмная тема через классы dark:.
    - Стилизация через Tailwind peer-классы.
    - Работает без JavaScript.
--}}

@props([
    'name'      => null,
    'id'        => null,
    'value'     => '1',
    'checked'   => false,
    'required'  => false,
    'disabled'  => false,
    'error'     => null,
    'color'     => 'blue',
    'size'      => 'md',
    'class'     => null,
])

@php
    $id = $id ?? 'checkbox-pill-' . uniqid();
    $hasError = !empty($error);
    $isMultiple = is_array($checked);
    $isChecked = $isMultiple ? in_array($value, $checked ?? []) : (bool)$checked;

    // Полные классы для checked состояния каждого цвета (Стандартизировано под 600)
    $checkedColors = [
        'blue'   => 'peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600',
        'green'  => 'peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600',
        'red'    => 'peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600',
        'purple' => 'peer-checked:bg-purple-600 peer-checked:text-white peer-checked:border-purple-600',
        'orange' => 'peer-checked:bg-orange-600 peer-checked:text-white peer-checked:border-orange-600',
        'pink'   => 'peer-checked:bg-pink-600 peer-checked:text-white peer-checked:border-pink-600',        
        'slate'  => 'peer-checked:bg-slate-700 peer-checked:text-white peer-checked:border-slate-700 dark:peer-checked:bg-slate-600 dark:peer-checked:border-slate-600',
    ];
    $currentColor = $checkedColors[$color] ?? $checkedColors['blue'];

    // Размеры
    $sizeClasses = [
        'sm' => 'px-3 py-1 text-xs',
        'md' => 'px-4 py-1.5 text-sm',
        'lg' => 'px-5 py-2 text-base',
    ];
    $currentSize = $sizeClasses[$size] ?? $sizeClasses['md'];

    $pillClasses = cn(
        // Базовые стили
        $currentSize,
        'inline-flex items-center justify-center',
        'border rounded-full',
        'font-medium',
        'transition-all duration-200',
        
        // Unchecked состояние
        'bg-white dark:bg-gray-800',
        'text-gray-700 dark:text-gray-300',
        'border-gray-300 dark:border-gray-600',
        
        // МАГИЯ FIX: Ховер срабатывает ТОЛЬКО если чекбокс НЕ выбран (peer-not-checked)
        'peer-not-checked:hover:bg-gray-50 dark:peer-not-checked:hover:bg-gray-700/50',
        'peer-not-checked:hover:border-gray-400 dark:peer-not-checked:hover:border-gray-500',
        
        // Checked состояние (цвет)
        $currentColor,
        
        // Focus (Добавлен dark:ring-offset-slate-900)
        'peer-focus-visible:ring-2 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-blue-500 dark:peer-focus-visible:ring-offset-slate-900',
        
        // Disabled
        'peer-disabled:opacity-50 peer-disabled:cursor-not-allowed',
        
        // Error
        $hasError ? 'border-red-500 text-red-500 dark:border-red-500 dark:text-red-500' : '',
        
        // Cursor
        $disabled ? 'cursor-not-allowed' : 'cursor-pointer select-none',
        
        // Дополнительные классы
        $class ?? '',
    );
@endphp

<div class="inline-flex" {{ $attributes->except(['class']) }}>
    {{-- Нативный checkbox (скрыт через sr-only) --}}
    <input
        type="checkbox"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $isChecked ? 'checked' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        aria-required="{{ $required ? 'true' : 'false' }}"
        @if($hasError) aria-invalid="true" @endif
        class="peer sr-only"
    />

    {{-- НОВОЕ: Изменили span на label for=id, чтобы клик по тексту работал --}}
    <label for="{{ $id }}" class="{{ $pillClasses }}">
        {{ $slot }}
    </label>
</div>