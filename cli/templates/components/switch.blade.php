{{--
  Компонент: Switch (Toggle)
  Описание: Кастомный переключатель с Tailwind стилизацией.
  Верстальщик сам оборачивает компонент в <label> с текстом.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - name     : string – имя поля (обязательно для отправки формы)
    - id       : string – id (генерируется уникально, если не передан)
    - value    : mixed – значение switch (по умолчанию '1')
    - checked  : mixed – начальное состояние:
                          - bool (true/false)
                          - array для группы (редко используется)
    - required : bool – обязательно поле (по умолчанию false)
    - disabled : bool – отключено (по умолчанию false)
    - error    : string – текст серверной ошибки
    - color    : string – цвет при checked:
                          'blue' (по умолчанию), 'green', 'red',
                          'purple', 'orange', 'pink', 'gray'
    - size     : string – размер: 'sm' | 'md' (по умолчанию) | 'lg'
    - class    : string – дополнительные CSS-классы

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1 Базовый (верстальщик оборачивает в label)
    <label class="flex items-center gap-3 cursor-pointer">
        <x-switch name="notifications" />
        <span>Уведомления</span>
    </label>

  2 С предзаполнением
    <label class="flex items-center gap-3 cursor-pointer">
        <x-switch name="darkMode" :checked="true" />
        <span>Тёмная тема</span>
    </label>

  3 С описанием
    <label class="flex items-start gap-3 cursor-pointer">
        <x-switch name="newsletter" color="green" />
        <div>
            <div class="font-medium">Рассылка</div>
            <div class="text-sm text-gray-500">Получать еженедельные новости</div>
        </div>
    </label>

  4 Группа switch'ей (настройки)
    <div class="flex flex-col gap-4">
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="settings[email]" value="1" :checked="true" color="blue" />
            <span>Email уведомления</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="settings[sms]" value="1" color="green" />
            <span>SMS уведомления</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="settings[push]" value="1" color="purple" />
            <span>Push уведомления</span>
        </label>
    </div>

  5 С разными цветами
    <div class="flex flex-col gap-3">
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="c1" color="blue" />
            <span>Синий</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="c2" color="green" />
            <span>Зелёный</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="c3" color="red" />
            <span>Красный</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="c4" color="purple" />
            <span>Пурпурный</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="c5" color="orange" />
            <span>Оранжевый</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="c6" color="pink" />
            <span>Розовый</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="c7" color="gray" />
            <span>Серый</span>
        </label>
    </div>

  6 С разными размерами
    <div class="flex items-center gap-6">
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="s1" size="sm" />
            <span class="text-sm">Маленький</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="s2" size="md" />
            <span>Средний</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <x-switch name="s3" size="lg" />
            <span class="text-lg">Большой</span>
        </label>
    </div>

  7 Отключённый
    <label class="flex items-center gap-3 cursor-not-allowed opacity-50">
        <x-switch name="disabled" :checked="true" disabled />
        <span>Недоступно</span>
    </label>

  8 С серверной ошибкой (Laravel)
    <label class="flex items-center gap-3 cursor-pointer">
        <x-switch
            name="terms"
            error="{{ isset($errors) ? $errors->first('terms') : '' }}"
        />
        <span>Принимаю условия</span>
    </label>

  9 С восстановлением после ошибки валидации
    <label class="flex items-center gap-3 cursor-pointer">
        <x-switch
            name="agree"
            :checked="old('agree') ? true : false"
        />
        <span>Я согласен</span>
    </label>

  10 С иконкой и текстом
    <label class="flex items-center gap-3 cursor-pointer">
        <x-switch name="notify" color="purple" />
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span>Получать уведомления</span>
        </div>
    </label>

  ============================================================
  КАК ИСПОЛЬЗОВАТЬ
  ============================================================
    1. Верстальщик оборачивает <x-switch> в <label> с нужным текстом.
    2. Клик по label автоматически кликает скрытый input (нативное поведение).
    3. При checked: трек меняет цвет, ползунок двигается вправо.

  ============================================================
  ОТЛИЧИЯ ОТ CHECKBOX
  ============================================================
    - Switch: визуально переключатель (on/off)
    - Checkbox: квадратный с галочкой
    - Switch лучше подходит для настроек, toggle функций
    - Checkbox лучше для форм с множественным выбором

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Компонент НЕ содержит label и текст — верстальщик сам решает как оформить.
    - Поддерживается тёмная тема через классы dark: в Tailwind.
    - Стилизация реализована через peer-классы.
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
    'color'     => 'blue',
    'size'      => 'md',
    'class'     => null,
])

@php
    $id = $id ?? 'switch-' . uniqid();
    $hasError = !empty($error);
    $isMultiple = is_array($checked);
    $isChecked = $isMultiple ? in_array($value, $checked ?? []) : (bool)$checked;

    // Цвета для checked состояния
    $checkedColors = [
        'blue'   => 'peer-checked:bg-blue-600',
        'green'  => 'peer-checked:bg-green-600',
        'red'    => 'peer-checked:bg-red-600',
        'purple' => 'peer-checked:bg-purple-600',
        'orange' => 'peer-checked:bg-orange-600',
        'pink'   => 'peer-checked:bg-pink-600',
        'gray'   => 'peer-checked:bg-gray-700 dark:peer-checked:bg-gray-600',
    ];
    $currentColor = $checkedColors[$color] ?? $checkedColors['blue'];

    // Размеры: [track, thumb]
    $sizeClasses = [
        'sm' => ['track' => 'w-8 h-4', 'thumb' => 'w-3 h-3'],
        'md' => ['track' => 'w-11 h-6', 'thumb' => 'w-5 h-5'],
        'lg' => ['track' => 'w-14 h-7', 'thumb' => 'w-6 h-6'],
    ];
    $currentSize = $sizeClasses[$size] ?? $sizeClasses['md'];

    $trackClasses = cn(
        // Базовые стили
        $currentSize['track'],
        'relative inline-flex items-center',
        'rounded-full',
        'transition-colors duration-200',
        
        // Unchecked состояние
        'bg-gray-300 dark:bg-gray-600',
        
        // Checked состояние (цвет)
        $currentColor,
        
        // Checked состояние для потомка (thumb)
        'peer-checked:[&>span]:left-[calc(100%-0.125rem)]',
        'peer-checked:[&>span]:-translate-x-full',
        
        // Focus
        'peer-focus-visible:ring-2 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-blue-400',
        
        // Disabled
        'peer-disabled:opacity-50 peer-disabled:cursor-not-allowed',
        
        // Error
        $hasError ? 'ring-2 ring-red-500' : '',
        
        // Cursor
        $disabled ? 'cursor-not-allowed' : 'cursor-pointer',
        
        // Дополнительные классы
        $class ?? '',
    );

    $thumbClasses = cn(
        $currentSize['thumb'],
        'bg-white',
        'rounded-full',
        'shadow',
        'absolute',
        'top-1/2 -translate-y-1/2',
        'left-0.5', // Отступ 2px слева
        'transition-all duration-200',
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

    {{-- Трек с ползунком --}}
    <span class="{{ $trackClasses }}">
        {{-- Ползунок --}}
        <span class="{{ $thumbClasses }}"></span>
    </span>
</div>