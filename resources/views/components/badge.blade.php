{{--
  Компонент: Badge
  Описание: Маленькая метка для статусов, категорий, счётчиков.
             В стиле shadcn/ui.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - variant    : string – стиль бейджа. Варианты:
                    * default (по умолчанию) – серый
                    * primary – синий
                    * secondary – оранжевый
                    * success – зелёный
                    * warning – жёлтый
                    * danger – красный
                    * info – голубой
                    * outline – прозрачный с рамкой
    - size       : string – размер. Варианты:
                    * sm – маленький
                    * md (по умолчанию) – средний
                    * lg – большой
    - dot        : bool – показывать точку слева (индикатор)
    - pulsedot   : bool – показывать пульсирующую точку (для live статусов)
    - dismissible: bool – показывать крестик для закрытия
    - class      : string – дополнительные CSS-классы

  ============================================================
  СЛОТ (slot)
  ============================================================
    Внутрь компонента передаётся текст (или любой контент).

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  --------------------------------------------------------
  1. БАЗОВЫЕ ВАРИАНТЫ (variant)
  --------------------------------------------------------
  
  Все варианты:
    <div class="flex flex-wrap gap-2">
        <x-badge>Default</x-badge>
        <x-badge variant="primary">Primary</x-badge>
        <x-badge variant="secondary">Secondary</x-badge>
        <x-badge variant="success">Success</x-badge>
        <x-badge variant="warning">Warning</x-badge>
        <x-badge variant="danger">Danger</x-badge>
        <x-badge variant="info">Info</x-badge>
        <x-badge variant="outline">Outline</x-badge>
    </div>

  --------------------------------------------------------
  2. РАЗМЕРЫ (size)
  --------------------------------------------------------
  
  Все размеры:
    <div class="flex items-center gap-2">
        <x-badge size="sm">Small</x-badge>
        <x-badge size="md">Medium</x-badge>
        <x-badge size="lg">Large</x-badge>
    </div>

  --------------------------------------------------------
  3. С ТОЧКОЙ (dot)
  --------------------------------------------------------
  
  Статусы с индикатором:
    <div class="flex flex-wrap gap-2">
        <x-badge variant="success" dot>Онлайн</x-badge>
        <x-badge variant="warning" dot>Отошёл</x-badge>
        <x-badge variant="danger" dot>Оффлайн</x-badge>
        <x-badge variant="info" dot>Не беспокоить</x-badge>
    </div>

  --------------------------------------------------------
  4. С ПУЛЬСИРУЮЩЕЙ ТОЧКОЙ (pulsedot)
  --------------------------------------------------------
  
  Live статусы (активные процессы):
    <div class="flex flex-wrap gap-2">
        <x-badge variant="success" pulsedot>Запись идёт</x-badge>
        <x-badge variant="danger" pulsedot>В прямом эфире</x-badge>
        <x-badge variant="info" pulsedot>Обновляется</x-badge>
        <x-badge variant="warning" pulsedot>Синхронизация</x-badge>
    </div>

  Статус сервера:
    <div class="flex items-center gap-3">
        <div class="font-medium">Сервер API</div>
        <x-badge variant="success" pulsedot>Работает</x-badge>
    </div>

  Трансляция:
    <div class="flex items-center gap-2">
        <x-badge variant="danger" pulsedot size="lg">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
            </svg>
            LIVE
        </x-badge>
        <span class="text-sm text-gray-600">1.2K зрителей</span>
    </div>

  --------------------------------------------------------
  5. С КРЕСТИКОМ (dismissible)
  --------------------------------------------------------
  
  Теги с возможностью удаления (Alpine):
    <div x-data="{ tags: ['Laravel', 'Vue', 'Tailwind'] }" class="flex flex-wrap gap-2">
        <template x-for="tag in tags" :key="tag">
            <x-badge 
                variant="primary" 
                dismissible 
                @remove="tags = tags.filter(t => t !== tag)"
            >
                <span x-text="tag"></span>
            </x-badge>
        </template>
    </div>

  --------------------------------------------------------
  6. С ИКОНКАМИ
  --------------------------------------------------------
  
  Бейдж с иконкой слева:
    <x-badge variant="success">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        Проверено
    </x-badge>

  Бейдж с иконкой справа:
    <x-badge variant="info">
        Новая версия
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </x-badge>

  --------------------------------------------------------
  7. РЕАЛЬНЫЕ СЦЕНАРИИ
  --------------------------------------------------------
  
  Статус заказа:
    <div class="flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <span>Заказ #1234</span>
            <x-badge variant="success">Оплачен</x-badge>
        </div>
        <div class="flex items-center justify-between">
            <span>Заказ #1235</span>
            <x-badge variant="warning" pulsedot>В обработке</x-badge>
        </div>
        <div class="flex items-center justify-between">
            <span>Заказ #1236</span>
            <x-badge variant="danger">Отменён</x-badge>
        </div>
    </div>

  Счётчик уведомлений:
    <x-button variant="outline" size="icon" class="relative">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <x-badge variant="danger" size="sm" class="absolute -top-1 -right-1 px-1.5">
            5
        </x-badge>
    </x-button>

  Роли пользователя:
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-gray-300"></div>
        <div>
            <div class="font-medium">Иван Петров</div>
            <div class="flex gap-1 mt-1">
                <x-badge variant="primary" size="sm">Админ</x-badge>
                <x-badge variant="outline" size="sm">Редактор</x-badge>
            </div>
        </div>
    </div>

  Теги статьи:
    <article class="border rounded-lg p-4">
        <h3 class="font-bold text-lg">Как использовать Alpine.js</h3>
        <div class="flex flex-wrap gap-1 mt-2">
            <x-badge variant="outline" size="sm">JavaScript</x-badge>
            <x-badge variant="outline" size="sm">Alpine</x-badge>
            <x-badge variant="outline" size="sm">Frontend</x-badge>
        </div>
        <p class="mt-3 text-gray-600">Подробное руководство по Alpine.js...</p>
    </article>

  Версия продукта:
    <div class="flex items-center gap-2">
        <h2 class="text-2xl font-bold">Мой продукт</h2>
        <x-badge variant="info" size="sm">v2.1.0</x-badge>
        <x-badge variant="success" size="sm">Новое</x-badge>
    </div>

  --------------------------------------------------------
  8. С КАРТОЧКОЙ ТОВАРА
  --------------------------------------------------------
  
    <div class="border rounded-lg p-4 relative">
        <x-badge variant="danger" class="absolute top-2 right-2">-20%</x-badge>
        <h3 class="font-bold">Кроссовки Nike</h3>
        <div class="flex items-center gap-2 mt-2">
            <span class="text-xl font-bold">8 000 ₽</span>
            <span class="text-gray-400 line-through">10 000 ₽</span>
        </div>
        <div class="flex gap-1 mt-2">
            <x-badge variant="success" size="sm" dot>В наличии</x-badge>
        </div>
    </div>

  --------------------------------------------------------
  9. DASHBOARD СТАТУСЫ
  --------------------------------------------------------
  
    <div class="grid grid-cols-2 gap-4">
        <div class="border rounded-lg p-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">API Сервер</span>
                <x-badge variant="success" pulsedot size="sm">Online</x-badge>
            </div>
            <div class="mt-2 text-2xl font-bold">99.9%</div>
            <div class="text-xs text-gray-500">Uptime за 30 дней</div>
        </div>
        
        <div class="border rounded-lg p-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">База данных</span>
                <x-badge variant="warning" pulsedot size="sm">Syncing</x-badge>
            </div>
            <div class="mt-2 text-2xl font-bold">85%</div>
            <div class="text-xs text-gray-500">Нагрузка</div>
        </div>
    </div>

  ============================================================
  КАК ИСПОЛЬЗОВАТЬ
  ============================================================
    - Для статусов используйте variant (success, warning, danger)
    - Для категорий используйте outline или primary
    - Для счётчиков используйте size="sm" с абсолютным позиционированием
    - Для тегов с удалением используйте dismissible + Alpine
    - Для статических индикаторов используйте dot
    - Для live/активных процессов используйте pulsedot

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Компонент не требует JavaScript (кроме dismissible с Alpine).
    - Поддерживается тёмная тема через классы dark:.
    - Все варианты адаптированы под тёмную тему.
    - Слот принимает любой контент (текст, иконки, другие компоненты).
    - Pulsedot использует animate-pulse для эффекта пульсации.
--}}

@props([
    'variant'     => 'default',
    'size'        => 'md',
    'dot'         => false,
    'pulsedot'    => false,
    'dismissible' => false,
    'class'       => null,
])

@php
    // Базовые стили
    $baseClasses = cn(
        'inline-flex items-center gap-1 rounded-full font-medium',
        'transition-colors whitespace-nowrap',
    );

    // Варианты цветов
    $variants = [
        'default'   => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
        'primary'   => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'secondary' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
        'success'   => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'warning'   => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'danger'    => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'info'      => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300',
        'outline'   => 'border border-gray-300 text-gray-700 dark:border-gray-600 dark:text-gray-300',
    ];

    // Размеры
    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-0.5 text-xs',
        'lg' => 'px-3 py-1 text-sm',
    ];

    // Цвет точки по варианту
    $dotColors = [
        'default'   => 'bg-gray-500',
        'primary'   => 'bg-blue-500',
        'secondary' => 'bg-orange-500',
        'success'   => 'bg-green-500',
        'warning'   => 'bg-yellow-500',
        'danger'    => 'bg-red-500',
        'info'      => 'bg-cyan-500',
        'outline'   => 'bg-gray-500',
    ];

    $finalClasses = cn(
        $baseClasses,
        $variants[$variant] ?? $variants['default'],
        $sizes[$size] ?? $sizes['md'],
        $class ?? '',
    );

    $dotClass = $dotColors[$variant] ?? $dotColors['default'];
@endphp

<span class="{{ $finalClasses }}" {{ $attributes }}>
    {{-- Обычная точка-индикатор --}}
    @if ($dot)
        <span class="inline-block w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
    @endif

    {{-- Пульсирующая точка-индикатор --}}
    @if ($pulsedot)
        <span class="relative inline-flex">
            <span class="absolute inline-flex h-full w-full rounded-full {{ $dotClass }} opacity-75 animate-ping"></span>
            <span class="relative inline-flex w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
        </span>
    @endif

    {{-- Контент --}}
    {{ $slot }}

    {{-- Крестик для удаления --}}
    @if ($dismissible)
        <button
            type="button"
            @click="$dispatch('remove')"
            class="ml-0.5 inline-flex items-center justify-center rounded-full hover:bg-black/10 dark:hover:bg-white/10 transition-colors"
            aria-label="Удалить"
        >
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</span>