{{--
  Компонент: Spinner
  Описание: Индикатор загрузки (спиннер) с Tailwind стилизацией.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - size  : string – размер: 'xs' | 'sm' | 'md' (по умолчанию) | 'lg' | 'xl'
    - color : string – цвет: 'white' (по умолчанию), 'blue', 'gray', 'current'
    - class : string – дополнительные CSS-классы

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1 Базовый (белый, средний)
    <x-spinner />

  2 Разные размеры
    <x-spinner size="xs" />
    <x-spinner size="sm" />
    <x-spinner size="md" />
    <x-spinner size="lg" />
    <x-spinner size="xl" />

  3 Разные цвета
    <x-spinner color="blue" />
    <x-spinner color="gray" />
    <x-spinner color="white" />
    <x-spinner color="current" /> 
    
  4 Внутри текста
    <span class="flex items-center gap-2">
        <x-spinner size="sm" color="blue" />
        Загрузка...
    </span>

  5 На всю страницу (центровка)
    <div class="fixed inset-0 flex items-center justify-center bg-black/50">
        <x-spinner size="xl" />
    </div>

  6 С кастомными классами
    <x-spinner class="text-purple-500" size="lg" />

  ============================================================
  ИСПОЛЬЗОВАНИЕ С КНОПКОЙ
  ============================================================

  Базовый пример с Alpine.js:
    <x-button
        x-data="{ loading: false }"
        :disabled="loading"
        @click="loading = true; setTimeout(() => loading = false, 2000)"
    >
        <template x-if="loading">
            <x-spinner size="sm" />
        </template>
        <span x-show="!loading">Отправить</span>
        <span x-show="loading">Отправка...</span>
    </x-button>

  С заменой текста:
    <x-button
        x-data="{ loading: false }"
        :disabled="loading"
        @click="loading = true; setTimeout(() => loading = false, 2000)"
    >
        <span x-text="loading ? 'Загрузка...' : 'Загрузить'"></span>
        <template x-if="loading">
            <x-spinner size="sm" />
        </template>
    </x-button>

  С формой:
    <form x-data="{ submitting: false }" @submit.prevent="submitting = true; $el.submit()">
        <x-input name="email" label="Email" />
        <x-button type="submit" :disabled="submitting">
            <template x-if="submitting">
                <x-spinner size="sm" />
            </template>
            <span x-text="submitting ? 'Отправка...' : 'Отправить'"></span>
        </x-button>
    </form>

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Компонент использует Tailwind animate-spin для анимации.
    - Цвет 'current' наследует color от родителя (currentColor).
    - По умолчанию спиннер белый — для использования на цветных кнопках.
--}}

@props([
    'size'  => 'md',
    'color' => 'white',
    'class' => null,
])

@php
    $sizeClasses = [
        'xs' => 'w-3 h-3',
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-8 h-8',
        'xl' => 'w-12 h-12',
    ];
    $currentSize = $sizeClasses[$size] ?? $sizeClasses['md'];

    $colorClasses = [
        'white'   => 'text-white',
        'blue'    => 'text-blue-500',
        'gray'    => 'text-gray-500',
        'current' => 'text-current',
    ];
    $currentColor = $colorClasses[$color] ?? $colorClasses['white'];

    $spinnerClasses = cn(
        $currentSize,
        $currentColor,
        'animate-spin',
        $class ?? '',
    );
@endphp

<svg
    class="{{ $spinnerClasses }}"
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    aria-hidden="true"
>
    <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
    />
    <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
    />
</svg>