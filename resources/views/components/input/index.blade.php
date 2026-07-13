{{--
  Компонент: Input
  Описание: Универсальное поле ввода с валидацией, маской, переключателем пароля.
  
  ============================================================
   ПРОПСЫ
  ============================================================
    - type          : string – тип поля (text, email, password, tel, number, search)
    - name          : string – имя поля
    - id            : string – id (генерируется из name, если не передан)
    - value         : string – значение (для предзаполнения)
    - label         : string – текст лейбла
    - placeholder   : string – плейсхолдер
    - error         : string – текст ошибки (серверная)
    - showError     : bool - резервировать место для вывода ошибок валидации
    - required      : bool – обязательно ли поле
    - disabled      : bool – отключено
    - autofocus     : bool – автоматический фокус
    - wrapperClass  : string – классы для обёртки
    - class         : string – классы для инпута
    - iconLeft      : string – HTML иконки слева (можно передать SVG)
    - iconRight     : string – HTML иконки справа
    - clearable     : bool – показывать крестик для очистки
    - validation    : array – правила валидации (required, email, minlength, maxlength, pattern)
    - validationMode: string – когда проверять (oninput, onblur, change) – по умолчанию onblur
    - messages      : array – кастомные сообщения для правил
    - mask          : string – маска для IMask (может быть реактивной)
    - togglePassword: bool – показывать глазик для типа password (по умолчанию true)
    - onInput       : string – Alpine-выражение при вводе
    - onBlur        : string – Alpine-выражение при потере фокуса

  ============================================================
  СЛОТ (slot)
  ============================================================
    Внутрь компонента можно передать контент, который будет вставлен 
    в правую часть поля (например, кнопку). Он позиционируется абсолютно.

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  Базовое текстовое поле, используй validationMode="oninput" для реактивной валидации, но по дефолту стоит validationMode="onblur"
    <x-input name="username" validationMode="oninput" label="Имя" placeholder="Введите имя" />

  Поле с валидацией (обязательное, email, minlength, maxlength)
    <x-input
        name="email"
        type="email"
        label="Email"
        placeholder="example@mail.ru"
        :validation="['required' => true, 'email' => true, 'minlength' => 5, 'maxlength' => 50]"
        :messages="[
            'required' => 'Email обязателен',
            'email' => 'Введите корректный email',
            'minlength' => 'Минимум 5 символов',
            'maxlength' => 'Максимум 50 символов',
        ]"
    />

  Пароль с глазиком и очисткой
    <x-input
        name="password"
        type="password"
        label="Пароль"
        placeholder="Введите пароль"
        clearable
        :validation="['required' => true, 'minlength' => 8]"
    />

  Телефон с маской (IMask) ставим 10 так как одна уже идет по дефолту +7
    <x-input
        name="phone"
        type="tel"
        label="Телефон"
        placeholder="+7 (___) ___-__-__"
        mask="+7 (000) 000-00-00"
        :validation="['required' => true, 'minlength' => 10]"
        :messages="['minlength' => 'Введите 11 цифр номера']"
    />

    ВНИМАНИЕ: iconLeft и iconRight принимают RAW HTML. 
Если данные из пользовательского ввода, ОБЯЗАТЕЛЬНО санитизируйте их!

  Поиск с иконкой и кастомной кнопкой (через слот) абсолютной позиционирование это оправильно! просто выстави паддинги pl-10 pr-20
   <x-input name="search" type="search" placeholder="Поиск..." class="pl-10 pr-20" wrapperClass="mt-5"
            iconLeft='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'            
            clearable >
             <x-button variant="primary" size="sm" type="button" onclick="alert('Ищем !')"
                class="absolute right-1.5 top-1/2 -translate-y-1/2">
                Найти
            </x-button>
    </x-input>

  Можно задать паттерн для валидации  
    <x-input name="zip" label="Индекс" placeholder="123456" :validation="['maxlength' => '6', 'pattern' => '^[0-9]{6}$']" :messages="['pattern' => 'Введите 6 цифр']" />  

  Числовое поле с ограничением длины (maxlength работает через Alpine)
    <x-input
        name="age"
        type="number"
        label="Возраст"
        placeholder="18"
        :validation="['required' => true, 'maxlength' => 3]"
        min="18"
        max="99"
    />

  Кастомные обработчики onInput / onBlur

    <x-input
        name="email"
        type="email"
        label="Email"
        onInput="console.log('Ввод:', value)"
        onBlur="console.log('Потеря фокуса:', value)"
    />

  Серверная ошибка (Laravel)

    <x-input
        name="username"
        label="Имя"
        error="{{ isset($errors) ? $errors->first('username') : '' }}"
        :value="old('username')"
    />

  Интеграция с Alpine-формой
  
    <div class="p-6 border-amber-300 border rounded-md">
        <form class="flex flex-col gap-4" x-data="{
            form: { name: '', email: '' },
            handleSubmit() {
                // Валидация на уровне формы
                if (!this.form.name.trim() || !this.form.email.trim()) {
                    alert('Заполните все поля!');
                    return;
                }
                console.log('Отправка:', this.form);
                alert('Отправка !');
            }
        }" @submit.prevent="handleSubmit">
            <x-input name="name" label="Имя" x-model="form.name" :validation="['required' => true]" />
            <x-input name="email" type="email" label="Email" x-model="form.email" :validation="['required' => true, 'email' => true]" />
            <x-button variant="primary" type="submit">Отправить</x-button>
        </form>
    </div>

  Только для чтения  
   <x-input name="readonly" label="Только для чтения" value="Статичное значение" disabled />  


  С автофокусом при загрузке
   <x-input name="focus" label="С автофокусом" autofocus />


  Пример без зарезервированного места для вывода ошибки валидации

         <form class="p-4 flex flex-col gap-1">
             <x-input type="email" showError="false" placeholder="Email" 
                :validation="['required' => true, 'minlength' => 4]" class="border p-2 rounded"/>
             <x-input type="password" showError="false" placeholder="Пароль" class="border p-2 rounded"/>
             <x-button type="submit" class="bg-blue-600 text-white p-2 rounded">Войти</x-button>
         </form>

  ============================================================
  ПОДКЛЮЧЕНИЕ IMASK (для масок)
  ============================================================
    Установите пакет:
        npm install imask

    В файле resources/js/app.js добавьте:
        import IMask from 'imask';
        window.IMask = IMask;

  ============================================================
  КАСТОМИЗАЦИЯ СТИЛЕЙ
  ============================================================
    - Передайте классы через пропсы wrapperClass (для обёртки) и class (для инпута).
    - Используйте iconLeft / iconRight для иконок.
    - Для тёмной темы используйте классы dark: в вашем CSS.

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Все пропсы, кроме name, необязательны.
    - Поле с типом password автоматически получает глазик (можно отключить togglePassword).
    - Маска работает только если IMask загружен и передан проп mask.
    - Серверная ошибка (error) отображается, даже если клиентская валидация прошла.
--}}

@props([
    'type'           => 'text',
    'name'           => null,
    'id'             => null,
    'value'          => null,
    'label'          => null,
    'placeholder'    => null,
    'error'          => null,
    'showError'      => true,
    'required'       => false,
    'disabled'       => false,
    'autofocus'      => false,
    'wrapperClass'   => null,
    'class'          => null,
    'iconLeft'       => null,
    'iconRight'      => null,
    'clearable'      => false,
    'validation'     => [],
    'validationMode' => 'onblur',
    'messages'       => [],
    'mask'           => null,
    'togglePassword' => true,
    'onInput'        => null,
    'onBlur'         => null,
    'autocomplete'   => null,
])

@php
    $id = $id ?? 'input-' . uniqid();
    $hasError = !empty($error);
    $isPassword = $type === 'password';
    $showPasswordToggle = $isPassword && $togglePassword;
    $isClearable = $clearable && !$disabled && !$hasError;
    $hasIconLeft = !empty($iconLeft);
    $hasIconRight = !empty($iconRight) || $isClearable || $showPasswordToggle;

    $maxLengthAttr = $validation['maxlength'] ?? null;
    $hasMask = !empty($mask);

    // Нормализация булевых пропсов (защита от строковых значений)
    $showError = filter_var($showError, FILTER_VALIDATE_BOOLEAN);
    $required = filter_var($required, FILTER_VALIDATE_BOOLEAN);
    $disabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);
    $autofocus = filter_var($autofocus, FILTER_VALIDATE_BOOLEAN);
    $clearable = filter_var($clearable, FILTER_VALIDATE_BOOLEAN);
    $togglePassword = filter_var($togglePassword, FILTER_VALIDATE_BOOLEAN);

    $inputProps = [
        'value'              => $value,
        'validationRules'    => $validation,
        'validationMessages' => $messages,
        'validationMode'     => $validationMode,
        'isPassword'         => $isPassword,
        'type'               => $type,
        'showError'          => $showError,
        'maxLengthAttr'      => $maxLengthAttr,
        'hasMask'            => $hasMask,
        'onBlurCallback'     => $onBlur,
        'onInputCallback'    => $onInput,
        'serverError'        => $hasError,
        // НОВОЕ: Передаем переводы для Alpine JS
        'labelShowPassword'  => __('Show password', 'weblegko'),
        'labelHidePassword'  => __('Hide password', 'weblegko'),
    ];

    $inputClasses = cn(
        'w-full rounded-md border px-3 py-3 text-sm transition-colors bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100',
        'focus:outline-none focus:ring-2 focus:ring-offset-1 dark:ring-offset-slate-900 placeholder:text-slate-400',
        $type === 'search' ? 'search-cancel-none' : '',
        $hasIconLeft ? 'pl-12' : '',
        $hasIconRight ? 'pr-12' : '',
        $hasError || !empty($error)
            ? 'border-red-500 focus:ring-red-500'
            : 'border-slate-300 dark:border-slate-600 focus:ring-blue-500',
        $disabled ? 'opacity-50 cursor-not-allowed' : '',
        $class,
    );

    $wrapperClasses = cn('w-full', $wrapperClass);
@endphp

<div
    x-data="input({{ json_encode($inputProps) }})"
    class="{{ $wrapperClasses }}"
    x-modelable="value"
    @submit.window="handleFormSubmit($event)"
>
    {{-- Лейбл --}}
    @if ($label)
        <label
            for="{{ $id }}"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
        >
            {{ $label }}
            @if ($required || isset($validation['required']))
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        {{-- Иконка слева --}}
        @if ($hasIconLeft)
            <div class="absolute top-1/2 -translate-y-1/2 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                {!! $iconLeft !!}
            </div>
        @endif

        {{-- Само поле --}}
        <input
            :type="isPassword ? (showPassword ? 'text' : 'password') : '{{ $type }}'"
            name="{{ $name }}"
            id="{{ $id }}"
            :value="value"
            x-ref="input"
            placeholder="{{ $placeholder }}"
            aria-required="{{ $required || isset($validation['required']) ? 'true' : 'false' }}"
            :aria-invalid="(validationError || @js($error)) ? 'true' : 'false'"
            :aria-describedby="(validationError || @js($error)) ? '{{ $id }}-error' : null"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $autofocus ? 'autofocus' : '' }}
            @blur="handleBlur()"
            @input="handleInput($event)"
            mask="{{ $mask }}"
            @if($autocomplete) autocomplete="on" @else autocomplete="off" @endif
            class="{{ $inputClasses }}"
            :class="[{ 'border-red-500 focus:ring-red-500': validationError || serverError }]"
            :style="hasMask && !isMaskFilled ? 'color: #94a3b8;' : ''"
            @if($maxLengthAttr) maxlength="{{ $maxLengthAttr }}" @endif
            {{ $attributes->except(['class', 'wrapperClass', 'name', 'id', 'value', 'label', 'placeholder', 'error', 'required', 'disabled', 'autofocus', 'iconLeft', 'iconRight', 'clearable', 'validation', 'validationMode', 'messages', 'togglePassword', 'onInput', 'onBlur', 'type', 'mask', 'autocomplete']) }}
        />

        {{-- Иконки справа --}}
        @if ($hasIconRight)
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 space-x-1 text-slate-400">
                {{-- Глазик для пароля --}}
                @if ($showPasswordToggle)
                    <button
                        type="button"
                        @click="togglePasswordVisibility()"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900 rounded-md"
                        :aria-label="showPassword ? labelHidePassword : labelShowPassword"
                    >
                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.07 7.07l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                @endif

                {{-- Кнопка очистки --}}
                @if ($isClearable)
                    <button
                        type="button"
                        x-show="value !== null && value !== '' && value !== undefined"
                        x-cloak
                        @click="clear()"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-900 rounded-md"
                        aria-label="{{ __('Clear input', 'weblegko') }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @elseif($iconRight)
                    <span class="pointer-events-none">{!! $iconRight !!}</span>
                @endif
            </div>
        @endif

        {{-- Слот --}}
        {{ $slot ?? '' }}
    </div>

    {{-- Контейнер для ошибки с фиксированной высотой --}}
    @if ($showError)
        <div class="h-5 mt-1 relative">
            <p
                class="absolute inset-0 text-sm text-red-500 truncate"
                :id="'{{ $id }}-error'"
                role="alert"
                aria-live="polite"
                x-show="validationError || @js($error)"
                x-text="validationError || @js($error)"
            ></p>
        </div>
     @endif
</div>