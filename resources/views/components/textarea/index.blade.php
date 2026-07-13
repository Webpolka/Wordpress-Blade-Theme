{{--
  Компонент: Textarea
  Описание: Многострочное поле ввода с валидацией и счётчиком символов.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - name          : string – имя поля
    - id            : string – id (генерируется если не передан)
    - value         : string – значение (для предзаполнения)
    - label         : string – текст лейбла
    - placeholder   : string – плейсхолдер
    - error         : string – текст ошибки (серверная)
    - required      : bool – обязательно ли поле
    - disabled      : bool – отключено
    - autofocus     : bool – автоматический фокус
    - rows          : int – количество строк (по умолчанию 4)
    - wrapperClass  : string – классы для обёртки
    - class         : string – классы для textarea
    - validation    : array – правила валидации (required, minlength, maxlength)
    - validationMode: string – когда проверять (oninput, onblur, change) – по умолчанию onblur
    - messages      : array – кастомные сообщения для правил
    - onInput       : string – Alpine-выражение при вводе
    - onBlur        : string – Alpine-выражение при потере фокуса
    - counter       : bool – показывать счётчик символов (по умолчанию false)
    - maxlength     : int – максимальная длина (для счётчика и ограничения)
    - resize        : string – изменение размера: 'none' | 'vertical' (по умолчанию) | 'horizontal' | 'both'

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1 Базовое поле
    <x-textarea name="message" label="Сообщение" placeholder="Введите сообщение..." />

  2 С предзаполнением
    <x-textarea
        name="description"
        label="Описание"
        :value="old('description', 'Текст по умолчанию')"
    />

  3 С валидацией (обязательное, minlength, maxlength)
    <x-textarea
        name="bio"
        label="О себе"
        placeholder="Расскажите о себе..."
        :validation="['required' => true, 'minlength' => 10, 'maxlength' => 500]"
        :messages="[
            'required' => 'Поле обязательно',
            'minlength' => 'Минимум 10 символов',
            'maxlength' => 'Максимум 500 символов',
        ]"
    />

  4 С счётчиком символов
    <x-textarea
        name="tweet"
        label="Твит"
        counter
        maxlength="280"
        placeholder="Что происходит?"
    />

  5 С кастомным количеством строк
    <x-textarea
        name="content"
        label="Контент"
        :rows="10"
        placeholder="Длинный текст..."
    />

  6 С ограничением изменения размера
    <x-textarea
        name="notes"
        label="Заметки"
        resize="none"
        placeholder="Без изменения размера"
    />

  7 С кастомными обработчиками
    <x-textarea
        name="feedback"
        label="Отзыв"
        onInput="console.log('Ввод:', value)"
        onBlur="console.log('Потеря фокуса:', value)"
    />

  8 С серверной ошибкой (Laravel)
    <x-textarea
        name="message"
        label="Сообщение"
        error="{{ isset($errors) ? $errors->first('message') : '' }}"
        :value="old('message')"
    />

  9 Интеграция с Alpine-формой
    <form
        x-data="{ message: '' }"
        @submit.prevent="alert('Отправлено: ' + message)"
        class="flex flex-col gap-2"
    >
        <x-textarea
            name="message"
            label="Сообщение"
            x-model="message"
            :validation="['required' => true]"
        />
        <x-button type="submit" variant="primary">Отправить</x-button>
    </form>

  10 Отключённое поле
    <x-textarea
        name="readonly"
        label="Только для чтения"
        value="Статичное значение"
        disabled
    />

  11 С автофокусом
    <x-textarea
        name="focus"
        label="С автофокусом"
        autofocus
    />

  ============================================================
  КАК ИСПОЛЬЗОВАТЬ
  ============================================================
    - Для простого ввода текста используйте базовый вариант
    - Для ограничений используйте counter + maxlength
    - Для форм используйте validation + validationMode
    - Resize контролирует возможность изменения размера пользователем

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Все пропсы, кроме name, необязательны.
    - Counter показывает текущее количество символов и лимит.
    - Resize контролирует возможность изменения размера пользователем.
    - Поддерживается тёмная тема через классы dark:.
--}}

@props([
    'name'           => null,
    'id'             => null,
    'value'          => null,
    'label'          => null,
    'placeholder'    => null,
    'error'          => null,
    'required'       => false,
    'disabled'       => false,
    'autofocus'      => false,
    'rows'           => 4,
    'wrapperClass'   => null,
    'class'          => null,
    'validation'     => [],
    'validationMode' => 'onblur',
    'messages'       => [],
    'onInput'        => null,
    'onBlur'         => null,
    'counter'        => false,
    'maxlength'      => null,
    'resize'         => 'vertical',
])

@php
    $id = $id ?? 'textarea-' . uniqid();
    $hasError = !empty($error);

    $textareaProps = [
        'value'              => $value,
        'validationRules'    => $validation,
        'validationMessages' => $messages,
        'validationMode'     => $validationMode,
        'onBlurCallback'     => $onBlur,
        'onInputCallback'    => $onInput,
        'serverError'        => $hasError,
        'maxlength'          => $maxlength,
    ];

    $resizeClasses = [
        'none'       => 'resize-none',
        'vertical'   => 'resize-y',
        'horizontal' => 'resize-x',
        'both'       => 'resize',
    ];
    $resizeClass = $resizeClasses[$resize] ?? $resizeClasses['vertical'];

    $textareaClasses = cn(
        'w-full rounded-md border px-3 py-2 text-sm transition-colors bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder:text-slate-400',
        'focus:outline-none focus:ring-2 focus:ring-offset-1 dark:ring-offset-slate-900',
        $hasError
            ? 'border-red-500 focus:ring-red-500'
            : 'border-slate-300 dark:border-slate-600 focus:ring-blue-500',
        $disabled ? 'opacity-50 cursor-not-allowed' : '',
        $resizeClass,
        $class,
    );

    $wrapperClasses = cn('w-full flex flex-col', $wrapperClass);
@endphp

<div
    x-data="textarea({{ json_encode($textareaProps) }})"
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

    {{-- Textarea --}}
    <textarea
        name="{{ $name }}"
        id="{{ $id }}"
        rows="{{ $rows }}"
        x-ref="textarea"
        placeholder="{{ $placeholder }}"
        aria-required="{{ $required || isset($validation['required']) ? 'true' : 'false' }}"
        :aria-invalid="(validationError || @js($error)) ? 'true' : 'false'"
        :aria-describedby="(validationError || @js($error)) ? '{{ $id }}-error' : null"
        {{ ($required || isset($validation['required'])) ? 'required' : '' }} 
        {{ $disabled ? 'disabled' : '' }}
        {{ $autofocus ? 'autofocus' : '' }}
        @blur="handleBlur()"
        @input="handleInput($event)"
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        class="{{ $textareaClasses }}"
        :class="[{ 'border-red-500 focus:ring-red-500': validationError || serverError }]"
        {{ $attributes->except(['class', 'wrapperClass', 'name', 'id', 'value', 'label', 'placeholder', 'error', 'required', 'disabled', 'autofocus', 'rows', 'validation', 'validationMode', 'messages', 'onInput', 'onBlur', 'counter', 'maxlength', 'resize']) }}
    >{{ $value }}</textarea>

    {{-- Счётчик символов --}}
    @if ($counter && $maxlength)
        <div class="flex justify-end mt-1">
            <span
                class="text-xs"
                :class="{
                    'text-slate-500': charCount < {{ $maxlength }} * 0.8,
                    'text-orange-500': charCount >= {{ $maxlength }} * 0.8 && charCount < {{ $maxlength }},
                    'text-red-500': charCount >= {{ $maxlength }}
                }"
                x-text="`${charCount} / {{ $maxlength }}`"
            ></span>
        </div>
    @endif

    {{-- Ошибка --}}
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
</div>