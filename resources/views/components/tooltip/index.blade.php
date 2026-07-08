{{--
  Компонент: Tooltip
  Описание: Всплывающая подсказка при наведении или фокусе.
             Использует Alpine.js компонент 'tooltip'.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - position  : string – позиция подсказки:
                    * 'top' (по умолчанию)
                    * 'bottom'
                    * 'left'
                    * 'right'
    - delay     : int – задержка появления в мс (по умолчанию 200)
    - arrow     : bool – показывать стрелку (по умолчанию true)
    - theme     : string – тема: 'dark' (по умолчанию) | 'light'
    - color     : string – кастомные Tailwind классы для цветовой схемы
                           (переопределяет theme)
    - distance  : int – расстояние от элемента до tooltip в rem (по умолчанию 8)
    - text      : string – текст подсказки (если не используется slot)
    - class     : string – дополнительные CSS-классы для контейнера

  ============================================================
  СЛОТЫ (slots)
  ============================================================
    - default – триггер (элемент, при наведении на который показывается tooltip)
    - content – кастомный контент tooltip (если нужен больше чем просто текст)

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Базовый tooltip (сверху):
    <x-tooltip text="Подсказка сверху">
        <x-button>Hover me</x-button>
    </x-tooltip>

 1.1 Разные расстояния:
    <div class="flex gap-4">
        <x-tooltip text="Близко (0.25rem)" :distance="0.25">
            <x-button size="sm">4px</x-button>
        </x-tooltip>
        
        <x-tooltip text="Стандарт (0.5rem)" :distance="0.5">
            <x-button size="sm">8px</x-button>
        </x-tooltip>
        
        <x-tooltip text="Средне (1rem)" :distance="1">
            <x-button size="sm">16px</x-button>
        </x-tooltip>
        
        <x-tooltip text="Далеко (1.5rem)" :distance="1.5">
            <x-button size="sm">24px</x-button>
        </x-tooltip>
    </div>

  2. Разные позиции:
    <div class="flex gap-4">
        <x-tooltip text="Сверху" position="top">
            <x-button size="sm">Top</x-button>
        </x-tooltip>
        
        <x-tooltip text="Снизу" position="bottom">
            <x-button size="sm">Bottom</x-button>
        </x-tooltip>
        
        <x-tooltip text="Слева" position="left">
            <x-button size="sm">Left</x-button>
        </x-tooltip>
        
        <x-tooltip text="Справа" position="right">
            <x-button size="sm">Right</x-button>
        </x-tooltip>
    </div>

  3. Светлая тема:
    <x-tooltip text="Светлая подсказка" theme="light">
        <x-button variant="outline">Hover me</x-button>
    </x-tooltip>

 
  4. Кастомные цвета (через color пропс):
    <x-tooltip text="Синий" color="bg-blue-500 text-white">
        <x-button>Blue</x-button>
    </x-tooltip>

    <x-tooltip text="Зелёный" color="bg-green-500 text-white">
        <x-button>Green</x-button>
    </x-tooltip>

    <x-tooltip text="Градиент" color="bg-gradient-to-r from-blue-500 to-purple-500 text-white">
        <x-button>Gradient</x-button>
    </x-tooltip>

  5. Без стрелки:
    <x-tooltip text="Без стрелки" :arrow="false">
        <x-button>Hover me</x-button>
    </x-tooltip>

  6. С задержкой:
    <x-tooltip text="Появится через 500мс" :delay="500">
        <x-button>Hover me</x-button>
    </x-tooltip>

  7. Мгновенное появление:
    <x-tooltip text="Мгновенно" :delay="0">
        <x-button>Hover me</x-button>
    </x-tooltip>

  8. С кастомным контентом:
    <x-tooltip position="bottom">
        <x-button>Показать детали</x-button>
        
        <x-slot:content>
            <div class="text-center">
                <div class="font-semibold mb-1">Заголовок</div>
                <div class="text-xs opacity-80">Подробное описание</div>
            </div>
        </x-slot:content>
    </x-tooltip>

  9. С иконкой:
    <x-tooltip text="Удалить запись">
        <x-button variant="ghost" size="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </x-button>
    </x-tooltip>

  10. В таблице (подсказки для ячеек):
    <table class="w-full">
        <tbody>
            <tr>
                <td>
                    <x-tooltip text="Полное имя пользователя">
                        <span class="truncate max-w-[150px] inline-block">Иван Петров</span>
                    </x-tooltip>
                </td>
                <td>
                    <x-tooltip text="Статус аккаунта">
                        <x-badge variant="success">Активен</x-badge>
                    </x-tooltip>
                </td>
            </tr>
        </tbody>
    </table>

  11. На ссылках:
    <x-tooltip text="Перейти на главную страницу">
        <a href="/" class="text-blue-500 hover:underline">Главная</a>
    </x-tooltip>

  ============================================================
  ACCESSIBILITY
  ============================================================
    - role="tooltip" на контейнере подсказки
    - aria-describedby связывает триггер с tooltip
    - Показывается при фокусе (keyboard navigation)
    - Скрывается при потере фокуса

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Использует Alpine компонент 'tooltip'
    - Плавная анимация появления/искрытия
    - Работает на hover и focus
    - Не ломает layout (inline-block)
    - Тёмная тема через dark: классы
    - Поддерживает кастомный HTML контент
--}}

@props([
    'position' => 'top',
    'delay' => 200,
    'arrow' => true,
    'theme' => 'dark',
    'color'    => null,
    'distance' => 0.5,
    'text' => null,
    'class' => null,
])

@php
    // Проверяем есть ли контент для показа
    $hasContent = !empty($text) || isset($content);
@endphp


<div x-data="tooltip({{ json_encode([
    'position' => $position,
    'delay' => $delay,
    'arrow' => $arrow,
    'theme' => $theme,
    'color'    => $color,
    'distance' => $distance,
]) }})" class="relative inline-block {{ $class }}">
    {{-- Триггер --}}
    <div
        @if($hasContent)
            @mouseenter="show()"
            @mouseleave="hide()"
            @focus="show()"
            @blur="hide()"
            x-bind:aria-describedby="isOpen ? id : null"
        @endif
    >
        {{ $slot }}
    </div>

     @if($hasContent)
        {{-- Tooltip --}}
        <div
            x-show="isOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            x-bind:id="id"
            role="tooltip"
            x-bind:class="positionClasses"
            x-bind:style="distanceStyle"
            class="absolute z-50 text-xs font-medium rounded-md shadow-sm whitespace-nowrap pointer-events-none"
        >
            {{-- Контент --}}
            <div x-bind:class="themeClasses" class="px-3 py-1.5 rounded-md">
                @if (isset($content))
                    {{ $content }}
                @else
                    {{ $text }}
                @endif
            </div>

            {{-- Стрелка --}}
            @if ($arrow)
                <div
                    x-bind:class="[arrowClasses, arrowColorClasses]"
                    x-bind:style="arrowBgStyle"
                    class="absolute w-2 h-2"
                ></div>
            @endif
        </div>
    @endif
</div>
