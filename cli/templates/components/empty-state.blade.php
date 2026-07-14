{{--
  ============================================================
  Компонент: EmptyState (Пустое состояние)
  Описание: Красивая заглушка для каталогов, корзины, поиска 
             или списков, когда данных нет.
  ============================================================

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - title       (string) : Заголовок (крупный текст).
    - description (string) : Поясняющий текст (серый).
    - icon        (bool)   : Показывать ли иконку по умолчанию. По умолчанию: true.
    - class       (string) : Доп. классы для обертки.

  ------------------------------------------------------------
  СЛОТЫ (Slots)
  ------------------------------------------------------------
    - icon    : Кастомная SVG-иконка (переопределяет пропс icon).
    - default : Кнопки действий или любой другой контент снизу.

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Простая заглушка (по умолчанию):
    <x-empty-state 
        title="Корзина пуста" 
        description="Добавьте товары из каталога, чтобы оформить заказ." 
    />

  2. С кнопкой действия:
      
        <x-empty-state title="Ничего не найдено" description="Попробуйте изменить запрос.">
            <x-button variant="outline">Сбросить фильтры</x-button>
        </x-empty-state>

        <x-empty-state 
        title="Ничего не найдено" 
        description="Попробуйте изменить параметры поиска или сбросить фильтры."
    >
            <x-button variant="primary" @click="clearFilters()">
                Сбросить фильтры
            </x-button>
            <x-button variant="destructive">
                Сбросить поиск
            </x-button>
        </x-empty-state>

  3. Со своей иконкой:
    <x-empty-state 
        title="Страница не найдена" 
        description="К сожалению, такой страницы больше не существует, или она была перемещена."
    >        
        <x-slot:icon>
            <svg class="w-16 h-16 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </x-slot:icon>
        
        <x-button variant="primary">
            Вернуться на главную
        </x-button>
    </x-empty-state>
--}}

@props([
    'title'       => null,
    'description' => null,
    'icon'        => true,
    'class'       => null,
])

@php
    $wrapperClasses = cn(
        'flex flex-col items-center justify-center text-center py-12 px-4',
        $class
    );

    $iconWrapperClasses = 'flex items-center justify-center w-16 h-16 rounded-full bg-muted mb-3';
    
    // Дефолтная иконка (коробка/архив)
    $defaultIcon = '<svg class="w-16 h-16 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>';
@endphp

<div class="{{ $wrapperClasses }}">
       {{-- Иконка --}}
        @if ($icon !== false)
            <div class="{{ $iconWrapperClasses }}">
                @if (is_bool($icon))
                    {{-- Если передали true (по умолчанию), выводим дефолтную SVG --}}
                    {!! $defaultIcon !!}
                @else
                    {{-- Если передали свой SVG через слот, выводим его БЕЗ экранирования --}}
                    {!! $icon !!}
                @endif
            </div>
        @endif

    {{-- Заголовок --}}
    @if ($title)
        <h3 class="text-lg font-semibold text-foreground mb-1">
            {{ $title }}
        </h3>
    @endif

    {{-- Описание --}}
    @if ($description)
        <p class="text-sm text-muted-foreground max-w-sm mb-6">
            {{ $description }}
        </p>
    @endif

    {{-- Слот для кнопок --}}
    @if (isset($slot) && !empty(trim($slot)))
        <div class="flex flex-col sm:flex-row gap-2">
            {{ $slot }}
        </div>
    @endif
</div>