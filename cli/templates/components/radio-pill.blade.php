{{--
  Ок !  

  Компонент: Radio Pill
  Описание: Овальный radio button с текстом внутри.
  Верстальщик сам оборачивает компонент в <label>.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - name     : string – имя поля (обязательно, одинаковое для группы)
    - id       : string – id (генерируется уникально, если не передан)
    - value    : mixed – значение radio (обязательно для группы)
    - checked  : mixed – выбранное значение группы (строка/число) или bool
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

  1 Базовая группа
    <div class="flex flex-wrap gap-2">
        <label class="cursor-pointer">
            <x-radio-pill name="plan" value="basic">Базовый</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="plan" value="pro" color="green">Про</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="plan" value="enterprise" color="purple">Enterprise</x-radio-pill>
        </label>
    </div>

  2 С предзаполнением (одна опция выбрана)
    <div class="flex flex-wrap gap-2">
        <label class="cursor-pointer">
            <x-radio-pill name="period" value="month" :checked="'month'">Месяц</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="period" value="quarter" :checked="'month'">Квартал</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="period" value="year" :checked="'month'">Год</x-radio-pill>
        </label>
    </div>

  3 С разными цветами
    <div class="flex flex-wrap gap-2">
        <label class="cursor-pointer">
            <x-radio-pill name="c1" value="1" color="blue">Синий</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="c2" value="1" color="green">Зелёный</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="c3" value="1" color="red">Красный</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="c4" value="1" color="purple">Пурпурный</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="c5" value="1" color="orange">Оранжевый</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="c6" value="1" color="pink">Розовый</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="c7" value="1" color="gray">Серый</x-radio-pill>
        </label>
    </div>

  4 С разными размерами
    <div class="flex flex-wrap gap-2">
        <label class="cursor-pointer">
            <x-radio-pill name="s1" value="1" size="sm">Small</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="s2" value="1" size="md">Medium</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="s3" value="1" size="lg">Large</x-radio-pill>
        </label>
    </div>

  5 Выбор способа доставки (классический кейс)
    <div class="flex flex-wrap gap-2">
        <label class="cursor-pointer">
            <x-radio-pill name="delivery" value="courier" :checked="old('delivery')">
                 Курьер
            </x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="delivery" value="pickup" :checked="old('delivery')">
                 Самовывоз
            </x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="delivery" value="post" :checked="old('delivery')">
                 Почта
            </x-radio-pill>
        </label>
    </div>

  6 С иконкой внутри (через slot)
    <label class="cursor-pointer">
        <x-radio-pill name="notify" value="email" color="purple">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Email
            </span>
        </x-radio-pill>
    </label>

  7 Отключённый
    <label class="cursor-not-allowed">
        <x-radio-pill name="disabled" value="1" :checked="'1'" disabled>
            Недоступно
        </x-radio-pill>
    </label>

  8 С серверной ошибкой (Laravel)
    <div class="flex flex-wrap gap-2">
        <label class="cursor-pointer">
            <x-radio-pill
                name="gender"
                value="male"
                :checked="old('gender')"
                error="{{ isset($errors) ? $errors->first('gender') : '' }}"
            >
                Мужской
            </x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill
                name="gender"
                value="female"
                :checked="old('gender')"
                error="{{ isset($errors) ? $errors->first('gender') : '' }}"
            >
                Женский
            </x-radio-pill>
        </label>
    </div>

  9 Генерация из массива (цикл)
    @php
        $tariffs = [
            ['value' => 'starter', 'label' => 'Стартовый'],
            ['value' => 'pro',     'label' => 'Про'],
            ['value' => 'premium', 'label' => 'Премиум'],
        ];
    @endphp
    <div class="flex flex-wrap gap-2">
        @foreach($tariffs as $tariff)
            <label class="cursor-pointer">
                <x-radio-pill
                    name="tariff"
                    :value="$tariff['value']"
                    :checked="old('tariff')"
                    color="{{ $tariff['value'] === 'pro' ? 'green' : 'blue' }}"
                >
                    {{ $tariff['label'] }}
                </x-radio-pill>
            </label>
        @endforeach
    </div>

  10 Выбор цены (классический UX)
    <div class="flex flex-wrap gap-2">
        <label class="cursor-pointer">
            <x-radio-pill name="price" value="99" :checked="'299'">99 ₽/мес</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="price" value="299" :checked="'299'" color="green">299 ₽/мес</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="price" value="599" :checked="'299'">599 ₽/мес</x-radio-pill>
        </label>
        <label class="cursor-pointer">
            <x-radio-pill name="price" value="999" :checked="'299'">999 ₽/мес</x-radio-pill>
        </label>
    </div>

  ============================================================
  ОТЛИЧИЯ ОТ CHECKBOX-PILL
  ============================================================
    - Radio: в группе с одинаковым name выбран только ОДИН
    - Checkbox: можно выбрать НЕСКОЛЬКО
    - Используй radio-pill когда нужен единственный выбор
    - Используй checkbox-pill когда нужно несколько

  ============================================================
  КАК ИСПОЛЬЗОВАТЬ
  ============================================================
    1. Верстальщик оборачивает <x-radio-pill> в <label>.
    2. Все radio одной группы должны иметь одинаковый name.
    3. Каждому radio нужно уникальное value.
    4. Для предзаполнения передавайте в checked выбранное значение.
    5. Текст (или контент) передаётся через slot.
    6. Клик по label автоматически кликает скрытый input (нативное поведение).

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Компонент НЕ содержит label — верстальщик сам оборачивает.
    - Поддерживается тёмная тема через классы dark:.
    - Стилизация через Tailwind peer-классы.
    - Работает без JavaScript.
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
    $id = $id ?? 'radio-pill-' . uniqid();
    $hasError = !empty($error);
    
    // Определяем isChecked:
    // - Если checked это массив — проверяем наличие value
    // - Если checked это bool/null — используем как bool
    // - Если checked это строка/число — сравниваем с value
    if (is_array($checked)) {
        $isChecked = in_array($value, $checked);
    } elseif (is_bool($checked) || is_null($checked)) {
        $isChecked = (bool) $checked;
    } else {
        $isChecked = (string) $checked === (string) $value;
    }

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
        'border rounded-full', // Изменил border-2 на border для консистентности
        'font-medium',
        'transition-all duration-200',
        
        // Unchecked состояние
        'bg-white dark:bg-gray-800',
        'text-gray-700 dark:text-gray-300',
        'border-gray-300 dark:border-gray-600',
        
        // НОВОЕ: Hover срабатывает ТОЛЬКО если не выбран (peer-not-checked)
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

    {{-- НОВОЕ: Изменили span на label for=id, чтобы клик по тексту работал --}}
    <label for="{{ $id }}" class="{{ $pillClasses }}">
        {{ $slot }}
    </label>
</div>