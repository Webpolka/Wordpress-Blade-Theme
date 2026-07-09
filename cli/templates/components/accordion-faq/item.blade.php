{{--
  ============================================================
  Компонент: Accordion Faq Item
  Описание: Пара "Вопрос - Ответ" для FAQ аккордеона.
  ============================================================

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - question : string – Текст вопроса (можно передать через слот)
    - class    : string – Дополнительные CSS-классы

  ------------------------------------------------------------
  СЛОТЫ (slots)
  ------------------------------------------------------------
    - default : Текст ответа
    - question: Кастомный HTML для вопроса (переопределяет пропс)
--}}

@props([
    'question' => null,
    'class'    => null,
])

{{-- Забираем настройки из родителя --}}
@aware([
    'multiple'  => false,
    'firstOpen' => false,
    'flush'     => false, 
])

@php
    // Стили самой плашечки
    $itemClasses = cn(
        $flush 
            ? '' // В режиме flush ничего не добавляем
            : 'transition-all duration-300 shadow-sm hover:scale-[101%] hover:shadow-md rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden',
        $class
    );
@endphp

<div
    x-data="{ id: 'faq-' + Math.random().toString(36).substr(2, 9) }"
    x-init="$nextTick(() => $dispatch('faq-register', { id }))"
    class="{{ $itemClasses }}"
>
    <h3>
        <button
            type="button"
            @click="toggle(id)"
            :aria-expanded="isOpen(id)"
            :aria-controls="'panel-' + id"
            class="flex w-full items-center justify-between gap-4 py-4 px-5 text-left font-medium text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
        >
            <span class="flex-1">
                @isset($question)
                    {{ $question }}
                @else
                    {{ $slot->question ?? '' }}
                @endisset
            </span>

            {{-- ПЛЮСИК, ПОВОРАЧИВАЮЩИЙСЯ НА 45 ГРАДУСОВ (СТАНОВИТСЯ КРЕСТИКОМ) --}}
            <svg
                class="h-5 w-5 flex-shrink-0 transition-transform duration-200 text-gray-400"
                :class="{ 'rotate-45': isOpen(id) }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </button>
    </h3>

    {{-- ФИКС АНИМАЦИИ: x-collapse анимирует этот блок (без padding) --}}
    <div
        x-show="isOpen(id)"
        x-collapse
        x-cloak
        :id="'panel-' + id"
        role="region"
        class="overflow-hidden"
    >
        {{-- А ОТСТУПЫ ДЕЛАЕМ У ВНУТРЕННЕГО БЛОКА --}}
        <div class="px-5 pb-5 text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
            {{ $slot }}
        </div>
    </div>
</div>