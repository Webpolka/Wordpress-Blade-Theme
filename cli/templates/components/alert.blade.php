{{--
  OK !  

  Компонент: Alert
  Описание: Блок уведомления для важных сообщений. В стиле shadcn/ui.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - variant     : string – тип уведомления. Варианты:
                    * info (по умолчанию) – информационное (синее)
                    * success – успешное (зелёное)
                    * warning – предупреждение (жёлтое)
                    * error – ошибка (красное)
    - title       : string – заголовок уведомления (опционально)
    - dismissible : bool – показывать крестик для закрытия (по умолчанию false)
    - icon        : string|bool – иконка слева:
                    * true (по умолчанию) – стандартная иконка для variant
                    * false – без иконки
                    * string – кастомный HTML (SVG)
    - class       : string – дополнительные CSS-классы

  ============================================================
  СЛОТЫ (slots)
  ============================================================
    - default – основной контент уведомления
    - title – кастомный заголовок (если нужен больше чем просто текст)
    - actions – блок действий (кнопки) справа или снизу

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  --------------------------------------------------------
  1. БАЗОВЫЕ ВАРИАНТЫ (variant)
  --------------------------------------------------------
  
  Все варианты:
    <x-alert variant="info">Это информационное сообщение.</x-alert>
    <x-alert variant="success">Операция выполнена успешно!</x-alert>
    <x-alert variant="warning">Внимание! Проверьте данные.</x-alert>
    <x-alert variant="error">Произошла ошибка. Попробуйте снова.</x-alert>

  --------------------------------------------------------
  2. С ЗАГОЛОВКОМ (title)
  --------------------------------------------------------
  
  Простой заголовок:
    <x-alert variant="success" title="Успешно">
        Ваши данные были сохранены.
    </x-alert>

  С кастомным заголовком через slot:
    <x-alert variant="error">
        <x-slot:title>
            <span class="font-bold">Ошибка 404</span>
            <span class="text-sm opacity-75">— страница не найдена</span>
        </x-slot:title>
        Проверьте правильность URL или вернитесь на главную.
    </x-alert>

  --------------------------------------------------------
  3. С ДЕЙСТВИЯМИ (actions slot)
  --------------------------------------------------------
  
  С кнопкой:
    <x-alert variant="warning" title="Сессия истекает">
        Ваша сессия истечёт через 5 минут.
        <x-slot:actions>
            <x-button size="sm" variant="outline" @click="extendSession()">
                Продлить
            </x-button>
        </x-slot:actions>
    </x-alert>

  С несколькими кнопками:
    <x-alert variant="info" title="Доступно обновление">
        Доступна новая версия приложения v2.1.0.
        <x-slot:actions>
            <x-button size="sm" variant="primary">Обновить</x-button>
            <x-button size="sm" variant="ghost">Позже</x-button>
        </x-slot:actions>
    </x-alert>

  --------------------------------------------------------
  4. ЗАКРЫВАЕМЫЕ (dismissible)
  --------------------------------------------------------
  
  С крестиком (Alpine):
    <div x-cloak x-data="{ show: true }" x-show="show" x-transition>
        <x-alert variant="success" title="Сохранено" dismissible @dismiss="show = false">
            Ваши изменения успешно сохранены.
        </x-alert>
    </div>

  Автоматическое закрытие через 5 секунд:
    <div x-cloak x-data="{ show: true }" x-show="show" x-transition
         x-init="setTimeout(() => show = false, 5000)">
        <x-alert variant="info" dismissible @dismiss="show = false">
            Это сообщение исчезнет через 5 секунд.
        </x-alert>
    </div>

  --------------------------------------------------------
  5. БЕЗ ИКОНКИ
  --------------------------------------------------------
  
    <x-alert variant="info" :icon="false">
        Сообщение без иконки.
    </x-alert>

  --------------------------------------------------------
  6. С КАСТОМНОЙ ИКОНКОЙ
  --------------------------------------------------------
  
    <x-alert variant="warning" :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z\'/></svg>'">
        Кастомная иконка предупреждения.
    </x-alert>

  --------------------------------------------------------
  7. РЕАЛЬНЫЕ СЦЕНАРИИ
  --------------------------------------------------------
  
  Сообщение после отправки формы (Laravel):
    @if (session('success'))
        <x-alert variant="success" title="Успешно" dismissible>
            {{ session('success') }}
        </x-alert>
    @endif

    @if (session('error'))
        <x-alert variant="error" title="Ошибка" dismissible>
            {{ session('error') }}
        </x-alert>
    @endif

  Ошибки валидации:
    @if ($errors->any())
        <x-alert variant="error" title="Проверьте форму">
            <ul class="list-disc list-inside text-sm mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

  Предупреждение о cookies:
    <div x-cloak x-data="{ show: !localStorage.getItem('cookiesAccepted') }" x-show="show" x-transition>
        <x-alert variant="info" title="Мы используем cookies" dismissible
                 @dismiss="show = false; localStorage.setItem('cookiesAccepted', '1')">
            Продолжая использовать сайт, вы соглашаетесь с нашей политикой конфиденциальности.
            <x-slot:actions>
                <x-button size="sm" variant="primary" @click="show = false; localStorage.setItem('cookiesAccepted', '1')">
                    Принять
                </x-button>
            </x-slot:actions>
        </x-alert>
    </div>

  Системное уведомление:
    <x-alert variant="warning" title="Технические работы">
        15 июля с 02:00 до 04:00 МСК будут проводиться технические работы. 
        В это время сервис может быть недоступен.
    </x-alert>

  Уведомление о новой версии:
    <x-alert variant="info" title="Доступно обновление v2.1.0">
        <ul class="list-disc list-inside text-sm mt-1 space-y-0.5">
            <li>Новый дизайн панели управления</li>
            <li>Ускорена загрузка страниц на 40%</li>
            <li>Исправлены ошибки в отчётах</li>
        </ul>
        <x-slot:actions>
            <x-button size="sm" variant="primary">Обновить сейчас</x-button>
            <x-button size="sm" variant="ghost">Подробнее</x-button>
        </x-slot:actions>
    </x-alert>

  Платёжная информация:
    <x-alert variant="success" title="Платёж принят">
        Спасибо! Ваш платёж на сумму <strong>1 500 ₽</strong> успешно обработан. 
        Чек отправлен на email.
        <x-slot:actions>
            <x-button size="sm" variant="outline">Скачать чек</x-button>
        </x-slot:actions>
    </x-alert>

  --------------------------------------------------------
  8. В ФОРМАХ
  --------------------------------------------------------
  
    <form method="POST" action="/subscribe" class="flex flex-col gap-4">
        @csrf
        
        @if (session('status'))
            <x-alert variant="success" dismissible>
                {{ session('status') }}
            </x-alert>
        @endif
        
        <x-input name="email" type="email" label="Email" placeholder="your@email.com" />
        <x-button type="submit" variant="primary">Подписаться</x-button>
    </form>

  ============================================================
  КАК ИСПОЛЬЗОВАТЬ
  ============================================================
    - Для успеха используйте variant="success"
    - Для ошибок используйте variant="error"
    - Для предупреждений используйте variant="warning"
    - Для информации используйте variant="info"
    - Для закрываемых уведомлений используйте dismissible + Alpine
    - Для сообщений с действиями используйте slot actions

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Компонент имеет role="alert" для accessibility.
    - Поддерживается тёмная тема через классы dark:.
    - Иконки по умолчанию для каждого варианта (можно отключить).
    - Dismissible диспатчит событие 'dismiss' при клике на крестик.
    - Слот actions позиционируется справа (на десктопе) или снизу (на мобильных).
--}}


@props([
    'variant'     => 'info',
    'title'       => null,
    'dismissible' => false,
    'icon'        => true,
    'class'       => null,
])

@php
    // Варианты стилей
    $variants = [
        'info' => [
            'container' => 'bg-blue-50 border-blue-200 text-blue-900 dark:bg-blue-950/50 dark:border-blue-800 dark:text-blue-100',
            'icon'      => 'text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300',
            'title'     => 'text-blue-900 dark:text-blue-100',
            'defaultIcon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        ],
        'success' => [
            'container' => 'bg-green-50 border-green-200 text-green-900 dark:bg-green-950/50 dark:border-green-800 dark:text-green-100',
            'icon'      => 'text-green-500 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300',
            'title'     => 'text-green-900 dark:text-green-100',
            'defaultIcon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        ],
        'warning' => [
            'container' => 'bg-yellow-50 border-yellow-200 text-yellow-900 dark:bg-yellow-950/50 dark:border-yellow-800 dark:text-yellow-100',
            'icon'      => 'text-yellow-600 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300',
            'title'     => 'text-yellow-900 dark:text-yellow-100',
            'defaultIcon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
        ],
        'error' => [
            'container' => 'bg-red-50 border-red-200 text-red-900 dark:bg-red-950/50 dark:border-red-800 dark:text-red-100',
            'icon'      => 'text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300',
            'title'     => 'text-red-900 dark:text-red-100',
            'defaultIcon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        ],
    ];

    $currentVariant = $variants[$variant] ?? $variants['info'];

    $containerClasses = cn(
        'relative border rounded-lg p-4',
        $currentVariant['container'],
        $class ?? '',
    );
@endphp

<div class="{{ $containerClasses }}" role="alert" {{ $attributes }}>
    <div class="flex gap-3">
        {{-- Иконка --}}
        @if ($icon !== false)
        <div class="block shrink-0">
            <div class="{{ $currentVariant['icon'] }}">
                @if (is_string($icon) && $icon !== '1')
                    {!! $icon !!}
                @else
                    {!! $currentVariant['defaultIcon'] !!}
                @endif
            </div>
        </div>
        @endif

        {{-- Контент --}}
        <div class="flex-1 min-w-0">
            {{-- Заголовок (Упростил логику) --}}
            @if ($title)
                <div class="font-semibold {{ $currentVariant['title'] }} mb-1">
                    {{ $title }}
                </div>
            @endif

            {{-- Основной текст --}}
            <div class="text-sm leading-relaxed [&_ul]:mt-1 [&_ol]:mt-1">
                {{ $slot }}
            </div>

            {{-- Действия (Slot) --}}
            @isset($actions)
                <div class="flex flex-wrap gap-2 mt-3">
                    {{ $actions }}
                </div>
            @endisset
        </div>

        {{-- Крестик --}}
        @if ($dismissible)
        <div class="block shrink-0">
            <button
                type="button"
                @click="$dispatch('dismiss')"
                class="-m-1.5 p-3 rounded-md inline-flex items-start justify-center transition-colors cursor-pointer {{ $currentVariant['icon'] }}"
                aria-label="{{ __('Close', 'weblegko') }}"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @endif
    </div>
</div>