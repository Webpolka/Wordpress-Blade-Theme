{{--
  Ок !

  Компонент: Radio
  Описание: Кастомный radio button с Tailwind стилизацией.
  Верстальщик сам оборачивает компонент в <label> с текстом.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - name     : string – имя поля (обязательно, одинаковое для группы radio)
    - id       : string – id (генерируется уникально, если не передан)
    - value    : mixed – значение radio (обязательно для группы)
    - checked  : mixed – начальное состояние:
                          - bool (true/false) для одиночного
                          - string/number — выбранное значение для группы
    - required : bool – обязательно поле (по умолчанию false)
    - disabled : bool – отключено (по умолчанию false)
    - error    : string – текст серверной ошибки
    - color    : string – цвет при checked:
                          'blue' (по умолчанию), 'green', 'red',
                          'purple', 'orange', 'pink', 'gray'
    - size     : string – размер: 'sm' | 'md' (по умолчанию) | 'lg'
    - class    : string – дополнительные CSS-классы для radio box

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1 Базовая группа radio (верстальщик оборачивает в label)
    <div class="flex flex-col gap-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="gender" value="male" />
            <span>Мужской</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="gender" value="female" />
            <span>Женский</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="gender" value="other" />
            <span>Другой</span>
        </label>
    </div>

  2 С предзаполнением (одно из группы выбрано)
    <div class="flex flex-col gap-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="plan" value="basic" :checked="'basic'" />
            <span>Базовый</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="plan" value="pro" :checked="'basic'" />
            <span>Профессиональный</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="plan" value="enterprise" :checked="'basic'" />
            <span>Корпоративный</span>
        </label>
    </div>

  3 Горизонтальная группа
    <div class="flex gap-4">
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="delivery" value="courier" />
            <span>Курьер</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="delivery" value="pickup" />
            <span>Самовывоз</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="delivery" value="post" />
            <span>Почта</span>
        </label>
    </div>

  4 С разными цветами
    <div class="flex gap-4">
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="c1" value="1" color="blue" />
            <span>Синий</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="c2" value="1" color="green" />
            <span>Зелёный</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="c3" value="1" color="red" />
            <span>Красный</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="c4" value="1" color="purple" />
            <span>Пурпурный</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="c5" value="1" color="orange" />
            <span>Оранжевый</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="c6" value="1" color="pink" />
            <span>Розовый</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="c7" value="1" color="gray" />
            <span>Серый</span>
        </label>
    </div>

  5 С разными размерами
    <div class="flex items-center gap-4">
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="s1" value="1" size="sm" />
            <span class="text-sm">Маленький</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="s2" value="1" size="md" />
            <span>Средний</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio name="s3" value="1" size="lg" />
            <span class="text-lg">Большой</span>
        </label>
    </div>

  6 С описанием
    <div class="flex flex-col gap-3">
        <label class="flex items-start gap-3 cursor-pointer">
            <x-radio name="plan" value="basic" :checked="'pro'" />
            <div>
                <div class="font-medium">Базовый</div>
                <div class="text-sm text-gray-500">100 ₽/мес — базовые функции</div>
            </div>
        </label>
        <label class="flex items-start gap-3 cursor-pointer">
            <x-radio name="plan" value="pro" :checked="'pro'" color="green" />
            <div>
                <div class="font-medium">Профессиональный</div>
                <div class="text-sm text-gray-500">500 ₽/мес — все функции</div>
            </div>
        </label>
    </div>

  7 Отключённый
    <label class="flex items-center gap-2 cursor-not-allowed opacity-50">
        <x-radio name="disabled" value="1" :checked="true" disabled />
        <span>Недоступно</span>
    </label>

  8 С серверной ошибкой (Laravel)
    <div class="flex flex-col gap-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio
                name="gender"
                value="male"
                error="{{ isset($errors) ? $errors->first('gender') : '' }}"
            />
            <span>Мужской</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <x-radio
                name="gender"
                value="female"
                error="{{ isset($errors) ? $errors->first('gender') : '' }}"
            />
            <span>Женский</span>
        </label>
    </div>

  9 С восстановлением после ошибки валидации
    <label class="flex items-center gap-2 cursor-pointer">
        <x-radio name="gender" value="male" :checked="old('gender')" />
        <span>Мужской</span>
    </label>

  10 Цикл для генерации группы
    @php
        $countries = [
            ['value' => 'ru', 'label' => 'Россия'],
            ['value' => 'us', 'label' => 'США'],
            ['value' => 'de', 'label' => 'Германия'],
        ];
    @endphp
    <div class="flex flex-col gap-2">
        @foreach($countries as $country)
            <label class="flex items-center gap-2 cursor-pointer">
                <x-radio name="country" :value="$country['value']" :checked="old('country')" />
                <span>{{ $country['label'] }}</span>
            </label>
        @endforeach
    </div>

  ============================================================
  КАК ИСПОЛЬЗОВАТЬ
  ============================================================
    1. Верстальщик оборачивает <x-radio> в <label> с нужным текстом.
    2. Все radio одной группы должны иметь одинаковый name.
    3. Каждому radio нужно уникальное value.
    4. Для предзаполнения передавайте выбранное значение в checked.
    5. Клик по label автоматически кликает скрытый input (нативное поведение).

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Компонент НЕ содержит label и текст — верстальщик сам решает как оформить.
    - Все radio одной группы должны иметь одинаковый name.
    - Для предзаполнения: checked="значение" (не bool, а само значение).
    - Поддерживается тёмная тема через классы dark: в Tailwind.
    - Стилизация реализована через peer-классы + arbitrary variants.
    - Компонент не требует JavaScript — работает на чистом HTML/CSS.
--}}

@props([
    'name'      => null,
    'id'        => null,
    'value'     => '1',
    'checked'   => null,
    'required'  => false,
    'disabled'  => false,
    'error'     => null,
    'color'     => 'blue',
    'size'      => 'md',
    'class'     => null,
])

@php
    $id = $id ?? 'radio-' . uniqid();
    $hasError = !empty($error);
    
    // Определяем isChecked:
    // - Если checked это массив — проверяем наличие value в массиве
    // - Если checked это строка/число — сравниваем с value
    // - Если checked это bool — используем как есть
    if (is_array($checked)) {
        $isChecked = in_array($value, $checked);
    } elseif (is_bool($checked) || is_null($checked)) {
        $isChecked = (bool) $checked;
    } else {
        $isChecked = (string) $checked === (string) $value;
    }

    // Единая цветовая схема (Стандартизация: gray заменен на slate)
    $colorClasses = [
        'blue'   => [
            'unchecked' => 'border-gray-300 dark:border-gray-600',
            'checked'   => 'peer-checked:border-blue-600 peer-checked:[&>span]:bg-blue-600 peer-checked:[&>span]:scale-100',
        ],
        'green'  => [
            'unchecked' => 'border-gray-300 dark:border-gray-600',
            'checked'   => 'peer-checked:border-green-600 peer-checked:[&>span]:bg-green-600 peer-checked:[&>span]:scale-100',
        ],
        'red'    => [
            'unchecked' => 'border-gray-300 dark:border-gray-600',
            'checked'   => 'peer-checked:border-red-600 peer-checked:[&>span]:bg-red-600 peer-checked:[&>span]:scale-100',
        ],
        'purple' => [
            'unchecked' => 'border-gray-300 dark:border-gray-600',
            'checked'   => 'peer-checked:border-purple-600 peer-checked:[&>span]:bg-purple-600 peer-checked:[&>span]:scale-100',
        ],
        'orange' => [
            'unchecked' => 'border-gray-300 dark:border-gray-600',
            'checked'   => 'peer-checked:border-orange-600 peer-checked:[&>span]:bg-orange-600 peer-checked:[&>span]:scale-100',
        ],
        'pink'   => [
            'unchecked' => 'border-gray-300 dark:border-gray-600',
            'checked'   => 'peer-checked:border-pink-600 peer-checked:[&>span]:bg-pink-600 peer-checked:[&>span]:scale-100',
        ],
        // НОВОЕ: Заменили gray на slate
        'slate'  => [
            'unchecked' => 'border-gray-300 dark:border-gray-600',
            'checked'   => 'peer-checked:border-slate-700 peer-checked:[&>span]:bg-slate-700 peer-checked:[&>span]:scale-100 dark:peer-checked:border-slate-500 dark:peer-checked:[&>span]:bg-slate-500',
        ],
    ];
    $currentColor = $colorClasses[$color] ?? $colorClasses['blue'];

    // Размеры: [box, dot]
    $sizeClasses = [
        'sm' => ['box' => 'w-4 h-4', 'dot' => 'w-2 h-2'],
        'md' => ['box' => 'w-5 h-5', 'dot' => 'w-3 h-3'],
        'lg' => ['box' => 'w-6 h-6', 'dot' => 'w-4 h-4'],
    ];
    $currentSize = $sizeClasses[$size] ?? $sizeClasses['md'];

    $outerClasses = cn(
        // Базовые стили
        $currentSize['box'],
        'inline-flex items-center justify-center',
        'rounded-full border-2',
        'bg-white dark:bg-gray-800',
        'transition-all duration-200',
        
        // Unchecked состояние
        $currentColor['unchecked'],
        
        // НОВОЕ: Hover только если не выбран (peer-not-checked)
        'peer-not-checked:hover:border-gray-400 dark:peer-not-checked:hover:border-gray-500',
        
        // Checked состояние (цвет)
        $currentColor['checked'],
        
        // Focus (Добавлен dark:ring-offset-slate-900)
        'peer-focus-visible:ring-2 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-blue-500 dark:peer-focus-visible:ring-offset-slate-900',
        
        // Disabled
        'peer-disabled:opacity-50 peer-disabled:cursor-not-allowed',
        
        // Error (Добавлен dark:border-red-500)
        $hasError ? 'border-red-500 dark:border-red-500' : '',
        
        // Cursor
        $disabled ? 'cursor-not-allowed' : 'cursor-pointer select-none',
        
        // Дополнительные классы
        $class ?? '',
    );

    // НОВОЕ: Убрали absolute, так как родительский блок уже flex и отцентрует точку
    $dotClasses = cn(
        $currentSize['dot'],
        'rounded-full',
        'scale-0',
        'transition-transform duration-200',
    );
@endphp

<div class="inline-flex" {{ $attributes->except(['class']) }}>
    {{-- Нативный radio (скрыт через sr-only) --}}
    <input
        type="radio"
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

    {{-- НОВОЕ: Изменили span на label for=id, чтобы клик по кружку работал --}}
    <label for="{{ $id }}" class="{{ $outerClasses }}">
        {{-- Внутренняя точка --}}
        <span class="{{ $dotClasses }}"></span>
    </label>
</div>