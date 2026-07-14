{{--
  Компонент: Select
  Описание: Универсальный выпадающий список с одиночным и множественным выбором, поиском, иконками.

  ============================================================
  Стор `$store.select` – не используется (внутреннее состояние Alpine)
  ============================================================
  Компонент не требует глобального стора. Вся логика управляется через Alpine-компонент `select`.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - name          : string – имя поля (обязательно для отправки формы)
    - id            : string – id (генерируется уникально, если не передан)
    - label         : string – текст лейбла над полем
    - placeholder   : string – плейсхолдер, когда ничего не выбрано (по умолчанию 'Выберите...')
    - options       : array – массив опций: [['label' => '...', 'value' => '...', 'icon' => '...']]
    - value         : mixed – предустановленное значение (для одиночного) или массив (для множественного)
    - multiple      : bool – множественный выбор (по умолчанию false)
    - searchable    : bool – показывать поле поиска в выпадающем списке (по умолчанию true)
    - clearable     : bool – показывать крестик для очистки (только для одиночного выбора) (по умолчанию false)
    - disabled      : bool – отключено (по умолчанию false)
    - required      : bool – обязательно поле (по умолчанию false)
    - error         : string – текст серверной ошибки (отображается под полем)
    - validation    : array – правила валидации (поддерживается только 'required')
    - validationMode: string – когда проверять: 'oninput', 'onblur', 'change' (по умолчанию 'onblur')
    - messages      : array – кастомные сообщения для ошибок (например, ['required' => 'Выберите вариант'])
    - onInput       : string – Alpine-выражение при изменении значения (например, "console.log(value)")
    - onBlur        : string – Alpine-выражение при потере фокуса
    - maxChips      : int – количество видимых чипсов в мультиселекте (остальные скрыты за +N) (по умолчанию 3)
    - wrapperClass  : string – дополнительные CSS-классы для обёртки
    - class         : string – дополнительные CSS-классы для триггера

  ============================================================
  СЛОТЫ
  ============================================================
    - noResults – контент, когда ничего не найдено (например, 'Ничего не найдено')
    - option – кастомизация отдельной опции (продвинутый сценарий)

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1 Одиночный выбор с иконками
    <x-select
        name="country"
        label="Страна"
        :options="[
            ['label' => 'Россия', 'value' => 'ru', 'icon' => '🇷🇺'],
            ['label' => 'США', 'value' => 'us', 'icon' => '🇺🇸'],
        ]"
    />

  2 Множественный выбор с ограничением чипсов
    <x-select
        name="tags"
        label="Теги"
        multiple
        maxChips="2"
        :options="[
            ['label' => 'Laravel', 'value' => 'laravel'],
            ['label' => 'Vue.js', 'value' => 'vue'],
        ]"
    />

  3 С валидацией (обязательное поле)
    <x-select
        name="city"
        label="Город"
        :options="$cities"
        :validation="['required' => true]"
        :messages="['required' => 'Пожалуйста, выберите город']"
    />

  4 Без поиска (для коротких списков)
    <x-select
        name="gender"
        label="Пол"
        :searchable="false"
        :options="[
            ['label' => 'Мужской', 'value' => 'male'],
            ['label' => 'Женский', 'value' => 'female'],
        ]"
    />

  5 С предустановленным значением (множественный выбор)
    <x-select
        name="countries"
        label="Страны"
        multiple
        :value="['ru', 'us']"
        :options="$countries"
    />

  6 Интеграция с Alpine-формой (x-model)

   <form x-data="{ country: '' }" 
        @submit.prevent="console.log('Выбрано:', country)"
        @input="console.log('input событие на форме, country:', country)">

        <x-select
            name="country"      
            @input="country = $event.detail"
            :options="[
                ['label' => 'Россия', 'value' => 'ru', 'icon' => '🇷🇺'],
                ['label' => 'США', 'value' => 'us', 'icon' => '🇺🇸'],
            ]"        
        />
        <x-button type="submit">Отправить</x-button>
    </form>

  7 Отправка в обычной форме с клиентской валидацией
  
     <form  method="POST" action="/submit">
        @csrf
        <x-select
            name="city"
            label="Город"
            :options="$countries"
            :validation="['required' => true]"
            validationMode="oninput" 
            :messages="['required' => 'Пожалуйста, выберите город']"
        />
            <x-button type="submit">Отправить</x-button>
    </form>
    
    C логированием для oneselect и отменой отправки

    <form  method="POST" action="/submit" x-data @submit.prevent="console.log('FormData:', Object.fromEntries(new FormData($event.target)))">
        @csrf
        <x-select
            name="country"
            :options="[
                ['label' => 'Россия', 'value' => 'ru', 'icon' => '🇷🇺'],
                ['label' => 'США', 'value' => 'us', 'icon' => '🇺🇸'],
            ]"
        />
        <x-button type="submit">Отправить</x-button>
    </form>


    C логированием для multiselect и отменой отправки

    <form x-data method="POST" action="/submit" @submit.prevent="
        const fd = new FormData($event.target);
        const data = {};
        for (let [key, val] of fd.entries()) {
            if (data[key]) {
                data[key] = Array.isArray(data[key]) ? [...data[key], val] : [data[key], val];
            } else {
                data[key] = val;
            }
        }
        console.log('FormData:', data);
    ">
        @csrf
        <x-select
            name="countries"
            label="Страны"
            multiple       
            :options="$countries"
        />
        <x-button type="submit">Отправить</x-button>
    </form>


  8 С кастомными обработчиками событий
    <x-select
        name="category"
        label="Категория"
        :options="$categories"
        onInput="console.log('Выбрано:', value)"
        onBlur="console.log('Потеря фокуса')"
    />


  ============================================================
  КЛАВИАТУРНАЯ НАВИГАЦИЯ
  ============================================================
    - Стрелка вниз/вверх – перемещение по опциям
    - Enter / Пробел – выбор/снятие выбора
    - Escape – закрытие списка без изменений
    - Tab – закрытие списка и переход к следующему элементу

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Поле с типом множественного выбора (`multiple`) автоматически создаёт скрытые инпуты для отправки формы.
    - Данный компонент не имитирует скрытый селект, а только скрытые инпуты !
    - Приоритет ошибок: клиентская (`validation`) перекрывает серверную (`error`), если поле невалидно.
    - Поддерживается тёмная тема через классы `dark:` в Tailwind.
--}}


@props([
    'name'           => null,
    'id'             => null,
    'label'          => null,
    'placeholder'    => null,
    'options'        => [],
    'value'          => null,
    'multiple'       => false,
    'searchable'     => true,
    'clearable'      => false,
    'disabled'       => false,
    'required'       => false,
    'error'          => null,
    'validation'     => [],
    'validationMode' => 'onblur',
    'messages'       => [],
    'onInput'        => null,
    'onBlur'         => null,
    'maxChips'       => 3,
    'class'          => null,
    'wrapperClass'   => null,
])

@php
    $id             = $id ?? 'select-' . uniqid();
    $hasError       = !empty($error);
    $isMultiple     = $multiple;
    $isSearchable   = $searchable;
    $isClearable    = $clearable && !$disabled;
    $maxLengthAttr  = $validation['maxlength'] ?? null;
    $hasValidation  = !empty($validation);

    // НОВОЕ: Переводы для Alpine JS
    $labelPlaceholder = __('Select...', 'weblegko');
    $labelSearch      = __('Search...', 'weblegko');
    $labelNoResults   = __('No results found', 'weblegko');

    $inputProps = [
        'options'            => $options,
        'value'              => $value,
        'multiple'           => $isMultiple,
        'searchable'         => $isSearchable,
        'placeholder'        => $placeholder ?? $labelPlaceholder,
        'disabled'           => $disabled,
        'required'           => $required,
        'validationRules'    => $validation,
        'validationMessages' => $messages,
        'validationMode'     => $validationMode,
        'onBlurCallback'     => $onBlur,
        'onInputCallback'    => $onInput,
        'maxChips'           => $maxChips,
        'placeholderSearch'  => $labelSearch,
        'noResultsText'      => $labelNoResults,
    ];

    // Design System: Триггер
    $classes = cn(
        'w-full rounded-md border px-3 py-3 text-sm transition-colors bg-background text-foreground',
        'focus:outline-none focus:ring-2 focus:ring-offset-1 ring-offset-background placeholder:text-muted-foreground',
        $hasError || !empty($error)
            ? 'border-destructive focus:ring-destructive'
            : 'border-input focus:ring-ring',
        $disabled ? 'opacity-50 cursor-not-allowed' : '',
        $class ?? '',
    );

    $wrapperClasses = cn('w-full', $wrapperClass ?? '');
@endphp

<div
    x-data="select({{ json_encode($inputProps) }})"
    x-model="value"
    class="{{ $wrapperClasses }}"
    @click.outside="close()"
    @keydown="handleKeydown($event)"
    @keydown.escape="close()"
    @submit.window="handleFormSubmit($event)"
    {{ $attributes }}
>
    {{-- Лейбл --}}
    @if ($label)
        <span
            id="{{ $id }}-label"
            class="block text-sm font-medium text-foreground mb-1"
        >
            {{ $label }}
            @if ($required || isset($validation['required']))
                <span class="text-destructive">*</span>
            @endif
        </span>
    @endif

    {{-- Скрытое поле для отправки формы --}}
    @if ($name)
        @if ($isMultiple)
            <template x-for="val in (value || [])" :key="val">
                <input type="hidden" name="{{ $name }}[]" :value="val" />
            </template>
        @else
            <input type="hidden" name="{{ $name }}" :value="value" />
        @endif
    @endif

    <div class="relative">
        {{-- Триггер (поле, которое отображает выбранные значения) --}}
        <div
            id="{{ $id }}-trigger"
            aria-labelledby="{{ $id }}-label"
            class="{{ $classes }} flex items-center cursor-pointer"
            @click="toggle()"
            @focus="focused = true"
            @blur="handleBlur(); focused = false"
            x-ref="trigger"
            tabindex="0"
            role="combobox"
            aria-haspopup="listbox"
            :aria-expanded="isOpen"
            aria-controls="{{ $id }}-listbox"
        >
            {{-- Контейнер для выбранных значений --}}
            <div
                x-data="chipsDrag()"
                @mousedown="startDrag($event)"
                @mousemove="moveDrag($event)"
                @mouseup="endDrag()"
                @mouseleave="endDrag()"
                @touchstart="startDrag($event)"
                @touchmove="moveDrag($event)"
                @touchend="endDrag()"
                x-ref="chipsContainer"
                class="flex-1 flex items-center gap-1 overflow-x-auto min-w-0 scrollbar-hide"
                :class="{ 'cursor-grab': canScroll }"
            >
                @if ($isMultiple)
                    {{-- Чипсы выбранных опций --}}
                    <template x-for="(opt, index) in selectedOptions" :key="opt.value">
                        <span
                            x-show="index < maxChips"
                            class="inline-flex items-center gap-1 bg-secondary text-secondary-foreground px-2 py-0.5 rounded text-xs whitespace-nowrap shrink-0"
                        >
                            <span x-text="opt.label"></span>
                            <button
                                type="button"
                                @click.stop="removeOption(opt.value)"
                                class="hover:text-destructive leading-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-destructive rounded"
                            >×</button>
                        </span>
                    </template>

                    {{-- Счётчик скрытых чипсов --}}
                    <span
                        x-show="selectedOptions.length > maxChips"
                        x-text="`+${selectedOptions.length - maxChips}`"
                        class="text-sm text-muted-foreground whitespace-nowrap shrink-0"
                    ></span>

                    {{-- Плейсхолдер --}}
                    <span
                        x-show="selectedOptions.length === 0"
                        x-text="placeholder"
                        class="text-muted-foreground whitespace-nowrap shrink-0"
                    ></span>
                @else
                    {{-- Одиночный выбор --}}
                    <span
                        x-show="!value"
                        x-text="placeholder"
                        class="text-muted-foreground"
                    ></span>
                    <span
                        x-show="value"
                        x-text="selectedLabel"
                        class="truncate"
                    ></span>
                @endif
            </div>

            {{-- Иконки справа (очистка и стрелка) --}}
            <div class="flex items-center gap-1 shrink-0 ml-2">
                @if ($isClearable && !$isMultiple)
                    <button
                        type="button"
                        x-show="value"
                        @click.stop="clear()"
                        class="text-muted-foreground hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring rounded"
                        aria-label="{{ __('Clear', 'weblegko') }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
                <svg
                    class="w-4 h-4 shrink-0 transition-transform duration-200 text-muted-foreground"
                    :class="isOpen ? 'rotate-180' : ''"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        {{-- Выпадающий список --}}
        <div
            x-show="isOpen"
            x-ref="listbox"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 mt-1 w-full max-h-60 overflow-y-auto bg-popover text-popover-foreground border border-border rounded-md shadow-lg"
            role="listbox"
            id="{{ $id }}-listbox"
            :aria-multiselectable="{{ $isMultiple ? 'true' : 'false' }}"
        >
            {{-- Поиск --}}
            @if ($isSearchable)
                <div class="sticky top-0 bg-popover p-2 border-b border-border z-10">
                    <input
                        x-ref="searchInput"
                        id="{{ $id }}-searchInput"
                        type="text"
                        x-model="searchQuery"
                        :placeholder="placeholderSearch"
                        class="w-full px-3 py-1 text-sm border border-input rounded bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-1 ring-offset-background"
                    />
                </div>
            @endif

            {{-- Опции --}}
            <template x-for="(opt, index) in filteredOptions" :key="opt.value">
                <div
                    @click="selectOption(opt)"
                    @mouseenter="highlightedIndex = index"
                    class="flex items-center gap-2 px-3 py-2 cursor-pointer hover:bg-accent"
                    :class="{ 'bg-accent': highlightedIndex === index }"
                    role="option"
                    :aria-selected="isSelected(opt)"
                >
                    {{-- Иконка (если есть) --}}
                    <span x-show="opt.icon" x-text="opt.icon" class="text-lg"></span>

                    {{-- Лейбл --}}
                    <span class="flex-1" x-text="opt.label"></span>

                    {{-- Галочка --}}
                    <svg
                        x-show="isSelected(opt)"
                        class="w-4 h-4 text-primary"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </template>

            {{-- Нет результатов --}}
            <div x-show="filteredOptions.length === 0" class="px-3 py-4 text-center text-muted-foreground text-sm">
                <span x-text="noResultsText"></span>
            </div>
        </div>
    </div>

    {{-- Ошибка --}}
    <div class="h-5 mt-1 relative">
        <p
            x-show="validationError || '{{ $error }}'"
            class="absolute inset-0 text-sm text-destructive truncate"
            x-text="validationError || '{{ $error }}'"
        ></p>
    </div>
</div>