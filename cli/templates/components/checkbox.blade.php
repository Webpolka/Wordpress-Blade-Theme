{{--
  ОК !  

  Компонент: Checkbox
  Описание: Кастомный checkbox с Tailwind стилизацией.
  Верстальщик сам оборачивает компонент в <label> с текстом.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - name     : string – имя поля (обязательно для отправки формы)
    - id       : string – id (генерируется уникально, если не передан)
    - value    : mixed – значение checkbox (по умолчанию '1')
    - checked  : mixed – начальное состояние:
                          - bool (true/false) для одиночного checkbox
                          - array для группы checkbox'ов
    - required : bool – обязательно поле (по умолчанию false)
    - disabled : bool – отключено (по умолчанию false)
    - error    : string – текст серверной ошибки (отображается через aria-invalid)
    - color    : string – цвет при checked:
                          'transparent' (по умолчанию) – прозрачный фон, галочка видна
                          'blue', 'green', 'red', 'purple', 'orange', 'pink'
    - size     : string – размер: 'sm' | 'md' (по умолчанию) | 'lg'
    - class    : string – дополнительные CSS-классы для box

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1 Базовый (верстальщик оборачивает в label)
    <label class="flex items-center gap-2 cursor-pointer">
        <x-checkbox name="agree" />
        <span>Я согласен с условиями</span>
    </label>

  2 С предзаполнением
    <label class="flex items-center gap-2 cursor-pointer">
        <x-checkbox name="subscribe" :checked="true" />
        <span>Подписаться на рассылку</span>
    </label>

  3 С описанием
    <label class="flex items-start gap-3 cursor-pointer">
        <x-checkbox name="newsletter" color="green" size="lg" />
        <div>
            <div class="font-medium">Подписаться на рассылку</div>
            <div class="text-sm text-gray-500">Еженедельные новости</div>
        </div>
    </label>

  4 Группа checkbox'ов (массив значений)
    <div class="flex flex-col gap-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <x-checkbox name="colors[]" value="red" :checked="['red', 'blue']" color="red" />
            <span>Красный</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-checkbox name="colors[]" value="green" :checked="['red', 'blue']" color="green" />
            <span>Зелёный</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-checkbox name="colors[]" value="blue" :checked="['red', 'blue']" color="blue" />
            <span>Синий</span>
        </label>
    </div>

  5 С разными цветами
    <div class="flex gap-3">
        <label class="flex items-center gap-2 cursor-pointer">
            <x-checkbox name="c1" color="blue" />
            <span>Синий</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-checkbox name="c2" color="green" />
            <span>Зелёный</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-checkbox name="c3" color="red" />
            <span>Красный</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-checkbox name="c4" color="purple" />
            <span>Пурпурный</span>
        </label>
    </div>

  6 С разными размерами
    <div class="flex items-center gap-4">
        <label class="flex items-center gap-2 cursor-pointer">
            <x-checkbox name="s1" size="sm" />
            <span class="text-sm">Маленький</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-checkbox name="s2" size="md" />
            <span>Средний</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-checkbox name="s3" size="lg" />
            <span class="text-lg">Большой</span>
        </label>
    </div>

  7 Отключённый
    <label class="flex items-center gap-2 cursor-not-allowed opacity-50">
        <x-checkbox name="disabled" :checked="true" disabled />
        <span>Отключённый</span>
    </label>

  8 С серверной ошибкой (Laravel)
    <label class="flex items-center gap-2 cursor-pointer">
        <x-checkbox
            name="terms"
            error="{{ isset($errors) ? $errors->first('terms') : '' }}"
        />
        <span>Принимаю условия</span>
    </label>

  9 С восстановлением после ошибки валидации
    <label class="flex items-center gap-2 cursor-pointer">
        <x-checkbox
            name="agree"
            :checked="old('agree') ? true : false"
        />
        <span>Я согласен</span>
    </label>

  10 С иконкой рядом с текстом
    <label class="flex items-center gap-2 cursor-pointer">
        <x-checkbox name="notify" color="purple" />
        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span>Получать уведомления</span>
    </label>

  ============================================================
  КАК ИСПОЛЬЗОВАТЬ
  ============================================================
    1. Верстальщик оборачивает <x-checkbox> в <label> с нужным текстом/иконками.
    2. Клик по label автоматически кликает скрытый input (нативное поведение).
    3. Клик по кастомному box (span) кликает input через onclick.
    4. Стилизация работает через Tailwind peer-классы.

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Компонент НЕ содержит label и текст — верстальщик сам решает как оформить.
    - Автоматически определяется одиночный режим (bool) или групповой (array)
      по типу значения checked.
    - Для группы checkbox'ов используйте name с [] (например, "colors[]").
    - Поддерживается тёмная тема через классы dark: в Tailwind.
    - Стилизация реализована через peer-классы + arbitrary variants.
    - Компонент не требует JavaScript — работает на чистом HTML/CSS.
--}}

@props([
    'name'      => null,
    'id'        => null,
    'value'     => '1',
    'checked'   => false,
    'required'  => false,
    'disabled'  => false,
    'error'     => null,
    'color'     => 'transparent',
    'size'      => 'md',
    'class'     => null,
])

@php
    $id = $id ?? 'checkbox-' . uniqid();
    $hasError = !empty($error);
    $isMultiple = is_array($checked);
    $isChecked = $isMultiple ? in_array($value, $checked ?? []) : (bool)$checked;

    // Design System: Привязка к семантическим переменным
    $colorClasses = [
        'transparent' => [
            'box' => 'peer-checked:bg-transparent peer-checked:border-primary',
            'icon' => 'text-primary'
        ],
        'blue'        => [
            'box' => 'peer-checked:bg-primary peer-checked:border-primary',
            'icon' => 'text-primary-foreground'
        ],
        'red'         => [
            'box' => 'peer-checked:bg-destructive peer-checked:border-destructive',
            'icon' => 'text-destructive-foreground'
        ],
        'slate'       => [
            'box' => 'peer-checked:bg-secondary peer-checked:border-secondary',
            'icon' => 'text-secondary-foreground'
        ],
        
        // Дополнительные цвета (оставляем Tailwind)
        'green'       => [
            'box' => 'peer-checked:bg-green-600 peer-checked:border-green-600',
            'icon' => 'text-white'
        ],
        'purple'      => [
            'box' => 'peer-checked:bg-purple-600 peer-checked:border-purple-600',
            'icon' => 'text-white'
        ],
        'orange'      => [
            'box' => 'peer-checked:bg-orange-600 peer-checked:border-orange-600',
            'icon' => 'text-white'
        ],
        'pink'        => [
            'box' => 'peer-checked:bg-pink-600 peer-checked:border-pink-600',
            'icon' => 'text-white'
        ],
    ];
    
    $currentColors = $colorClasses[$color] ?? $colorClasses['transparent'];

    $sizeClasses = [
        'sm' => ['box' => 'w-4 h-4', 'icon' => 'w-3 h-3'],
        'md' => ['box' => 'w-5 h-5', 'icon' => 'w-4 h-4'],
        'lg' => ['box' => 'w-6 h-6', 'icon' => 'w-5 h-5'],
    ];
    $currentSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="inline-flex" {{ $attributes->except(['class']) }}>
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

    <span
        class="
            {{ $currentSize['box'] }}
            inline-flex items-center justify-center
            border-2 rounded
            transition-all duration-200
            
            bg-background border-input
            
            {{ $currentColors['box'] }}
            
            peer-not-checked:hover:border-foreground/40
            peer-focus-visible:ring-2 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-ring peer-focus-visible:ring-offset-background
            peer-disabled:opacity-50 peer-disabled:cursor-not-allowed
            peer-checked:[&>svg]:opacity-100 peer-checked:[&>svg]:scale-100
            
            @if($hasError) border-destructive @endif
            {{ $disabled ? 'cursor-not-allowed' : 'cursor-pointer' }}
            {{ $class ?? '' }}
        "
    >
        <svg
            class="
                {{ $currentSize['icon'] }}
                {{ $currentColors['icon'] }}
                opacity-0 scale-50
                transition-all duration-150
            "
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            stroke-width="3"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
    </span>
</div>