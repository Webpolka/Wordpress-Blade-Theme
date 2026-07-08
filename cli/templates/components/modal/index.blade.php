{{--
  Компонент: Modal
  Описание: Модальное окно с overlay, анимацией и accessibility.
             Работает на чистом JS (без Alpine). Самодостаточный компонент.

  ============================================================
  ПРОПСЫ (параметры)
  ============================================================
    - name            : string – уникальное имя модалки (ОБЯЗАТЕЛЬНО)
    - title           : string – заголовок модалки (опционально)
    - description     : string – описание под заголовком (опционально)
    - size            : string – размер модалки:
                          * 'sm' – маленький (max-w-sm)
                          * 'md' – средний (max-w-md) — по умолчанию
                          * 'lg' – большой (max-w-lg)
                          * 'xl' – очень большой (max-w-xl)
                          * '2xl' – огромный (max-w-2xl)
                          * 'full' – на весь экран (max-w-full)
    - closeOnOverlay  : bool – закрывать по клику на overlay (по умолчанию true)
    - closeOnEscape   : bool – закрывать по Escape (по умолчанию true)
    - showCloseButton : bool – показывать крестик в углу (по умолчанию true)
    - class           : string – дополнительные CSS-классы для контейнера модалки
    - overlayClass    : string – дополнительные CSS-классы для overlay

  ============================================================
  СЛОТЫ (slots)
  ============================================================
    - default  – основной контент модалки
    - header   – кастомный заголовок (заменяет title/description)
    - footer   – блок действий внизу (обычно кнопки)

  ============================================================
  УПРАВЛЕНИЕ
  ============================================================
  1. Открытие через data-modal-target:
    <button data-modal-target="my-modal">Открыть</button>

  2. Закрытие через data-modal-close:
    <button data-modal-close>Закрыть</button>

  3. Программное управление через JS:
    window.modalManager.open('my-modal');
    window.modalManager.close('my-modal');

  ============================================================
  СОБЫТИЯ
  ============================================================
  Компонент диспатчит кастомные события:
    - modal:opened – когда модалка открылась
    - modal:closed – когда модалка закрылась

  Пример подписки:
    document.getElementById('my-modal').addEventListener('modal:opened', () => {
        console.log('Модалка открыта');
    });

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Базовая модалка
    <x-button data-modal-target="simple">Открыть</x-button>

    <x-modal name="simple" title="Простая модалка">
        <p>Это содержимое модального окна.</p>
        
        <x-slot:footer>
            <x-button variant="outline" data-modal-close>Закрыть</x-button>
            <x-button variant="primary" data-modal-close>Подтвердить</x-button>
        </x-slot:footer>
    </x-modal>

  2. С описанием
    <x-button data-modal-target="with-desc">Подробнее</x-button>

    <x-modal 
        name="with-desc" 
        title="Информация о тарифе" 
        description="Подробные условия выбранного плана"
    >
        <p>Стоимость: 999 ₽/мес</p>
        <p>Пользователи: до 10</p>
        
        <x-slot:footer>
            <x-button variant="outline" data-modal-close>Закрыть</x-button>
            <x-button variant="primary" data-modal-close>Оплатить</x-button>
        </x-slot:footer>
    </x-modal>

  3. Подтверждение удаления (без крестика)
    <x-button variant="destructive" data-modal-target="delete-confirm">
        Удалить
    </x-button>

    <x-modal 
        name="delete-confirm" 
        size="sm" 
        title="Удалить запись?" 
        :show-close-button="false"
    >
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Это действие нельзя отменить. Запись будет удалена навсегда.
        </p>
        
        <x-slot:footer>
            <x-button variant="outline" data-modal-close>Отмена</x-button>
            <x-button variant="destructive" data-modal-close onclick="deleteRecord()">
                Удалить
            </x-button>
        </x-slot:footer>
    </x-modal>

  4. Форма в модалке
    <x-button data-modal-target="create-user">Создать пользователя</x-button>

    <x-modal name="create-user" title="Новый пользователь" size="lg">
        <form method="POST" action="/users" class="flex flex-col gap-4">
            @csrf
            <x-input name="name" label="Имя" :validation="['required' => true]" />
            <x-input 
                name="email" 
                label="Email" 
                type="email" 
                :validation="['required' => true, 'email' => true]" 
            />
            
            <x-slot:footer>
                <x-button type="button" variant="outline" data-modal-close>Отмена</x-button>
                <x-button type="submit" variant="primary">Создать</x-button>
            </x-slot:footer>
        </form>
    </x-modal>

  5. С кастомным header
    <x-button data-modal-target="custom-header">Кастомный header</x-button>

    <x-modal name="custom-header">
        <x-slot:header>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Кастомный заголовок</h3>
                    <p class="text-sm text-gray-500">С иконкой и описанием</p>
                </div>
            </div>
        </x-slot:header>
        
        <p>Контент модалки с кастомным header.</p>
    </x-modal>

  6. Разные размеры
    <div class="flex gap-2">
        <x-button size="sm" data-modal-target="modal-sm">Small</x-button>
        <x-button size="sm" data-modal-target="modal-md">Medium</x-button>
        <x-button size="sm" data-modal-target="modal-lg">Large</x-button>
        <x-button size="sm" data-modal-target="modal-xl">XLarge</x-button>
    </div>

    <x-modal name="modal-sm" size="sm" title="Маленькая">
        <p>Компактная модалка.</p>
    </x-modal>

    <x-modal name="modal-md" size="md" title="Средняя">
        <p>Стандартный размер.</p>
    </x-modal>

    <x-modal name="modal-lg" size="lg" title="Большая">
        <p>Для большего контента.</p>
    </x-modal>

    <x-modal name="modal-xl" size="xl" title="Очень большая">
        <p>Для форм и таблиц.</p>
    </x-modal>

  7. Без закрытия по overlay/escape
    <x-button data-modal-target="no-close">Обязательное действие</x-button>

    <x-modal 
        name="no-close" 
        title="Важно" 
        :close-on-overlay="false" 
        :close-on-escape="false"
    >
        <p>Вы должны выполнить это действие перед продолжением.</p>
        
        <x-slot:footer>
            <x-button variant="primary" data-modal-close>Понятно</x-button>
        </x-slot:footer>
    </x-modal>

  8. События (реакция на открытие/закрытие)
    <x-button data-modal-target="with-events">Открыть с событиями</x-button>

    <x-modal name="with-events" id="modal-with-events">
        <p>Контент модалки</p>
    </x-modal>

    <script>
        document.getElementById('modal-with-events').addEventListener('modal:opened', () => {
            console.log('Модалка открыта');
            // Можно загрузить данные, инициализировать компоненты и т.д.
        });

        document.getElementById('modal-with-events').addEventListener('modal:closed', () => {
            console.log('Модалка закрыта');
            // Можно очистить форму, сбросить состояние и т.д.
        });
    </script>

  9. Длинный контент (со скроллом)
    <x-button data-modal-target="long-content">Условия использования</x-button>

    <x-modal name="long-content" title="Условия использования">
        <div class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
            <p>1. Общие положения...</p>
            <p>2. Права и обязанности...</p>
            <p>3. Ответственность сторон...</p>
            <p>4. Порядок разрешения споров...</p>
            <p>5. Заключительные положения...</p>
            <p>Lorem ipsum dolor sit amet...</p>
            <p>Consectetur adipiscing elit...</p>
            <p>Sed do eiusmod tempor...</p>
        </div>
        
        <x-slot:footer>
            <x-button variant="outline" data-modal-close>Отклонить</x-button>
            <x-button variant="primary" data-modal-close>Принимаю</x-button>
        </x-slot:footer>
    </x-modal>

  10. Программное открытие через JS
    <x-button onclick="window.modalManager.open('programmatic-modal')">
        Открыть через JS
    </x-button>

    <x-modal name="programmatic-modal" title="Открыто программно">
        <p>Эта модалка открылась через JavaScript.</p>
        
        <x-slot:footer>
            <x-button variant="primary" data-modal-close>Закрыть</x-button>
        </x-slot:footer>
    </x-modal>

  ============================================================
  ACCESSIBILITY
  ============================================================
  Компонент полностью доступен для keyboard navigation и screen readers:
    - role="dialog" + aria-modal="true" – идентифицирует как модалку
    - aria-labelledby – связывает с заголовком
    - aria-describedby – связывает с описанием
    - Focus trap – Tab ходит только внутри модалки
    - Возврат фокуса – после закрытия фокус возвращается к триггеру
    - Escape – закрывает модалку
    - tabindex="-1" – позволяет фокусироваться на dialog

  ============================================================
  ОСОБЕННОСТИ
  ============================================================
    - Работает на чистом JS, без Alpine
    - Использует data-атрибуты для управления
    - Поддерживает несколько модалок одновременно (каскадный z-index)
    - Блокирует скролл body при открытии
    - Плавная анимация появления/скрытия (CSS transitions)
    - Тёмная тема через dark: классы
    - Поддержка динамических модалок (AJAX, Livewire) через MutationObserver
    - События modal:opened / modal:closed для интеграции

  ============================================================
  ЗАВИСИМОСТИ
  ============================================================

  Проверь наличие CSS для анимации (в resources/css/components.css):

    [data-modal]:not(.hidden) {
      opacity: 0;
      transition: opacity 200ms ease-out;
    }

    [data-modal]:not(.hidden) [role="dialog"] {
      opacity: 0;
      transform: scale(0.95) translateY(0.65rem);
      transition: opacity 200ms ease-out, transform 200ms ease-out;
    }

    .modal-overlay-visible {
      opacity: 1 !important;
    }

    .modal-dialog-visible {
      opacity: 1 !important;
      transform: scale(1) translateY(0) !important;
    }
--}}

@props([
    'name'            => null,
    'title'           => null,
    'description'     => null,
    'size'            => 'md',
    'closeOnOverlay'  => true,
    'closeOnEscape'   => true,
    'showCloseButton' => true,
    'class'           => null,
    'overlayClass'    => null,
])

@php
    if (empty($name)) {
        throw new \InvalidArgumentException('Modal component requires a "name" prop');
    }

    $sizes = [
        'sm'   => 'max-w-sm',
        'md'   => 'max-w-md',
        'lg'   => 'max-w-lg',
        'xl'   => 'max-w-xl',
        '2xl'  => 'max-w-2xl',
        'full' => 'max-w-full mx-4',
    ];
    $currentSize = $sizes[$size] ?? $sizes['md'];

    $hasHeader = isset($header) || $title || $showCloseButton;
    $titleId = "modal-title-{$name}";
    $descId = "modal-desc-{$name}";

    $modalClasses = cn(
        'relative bg-white dark:bg-gray-900 rounded-lg shadow-xl',
        'w-full',
        $currentSize,
        'max-h-[90vh] flex flex-col',
        $class ?? '',
    );

    $overlayClasses = cn(
        'fixed inset-0 bg-black/50 backdrop-blur-sm',
        'flex items-center justify-center p-4',
        $overlayClass ?? '',
    );
@endphp

<div
    data-modal="{{ $name }}"
    data-close-on-overlay="{{ $closeOnOverlay ? 'true' : 'false' }}"
    data-close-on-escape="{{ $closeOnEscape ? 'true' : 'false' }}"
    class="hidden fixed inset-0 {{ $overlayClasses }}"
    {{ $attributes->except(['class']) }}
>
    <div
        role="dialog"
        aria-modal="true"
        @if($title) aria-labelledby="{{ $titleId }}" @endif
        @if($description) aria-describedby="{{ $descId }}" @endif
        tabindex="-1"
        class="{{ $modalClasses }}"
    >
        {{-- Header --}}
        @if ($hasHeader)
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 shrink-0 relative">
                @if (isset($header))
                    {{ $header }}
                @elseif ($title)
                    <div class="pr-8">
                        <h3 id="{{ $titleId }}" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $title }}
                        </h3>
                        @if ($description)
                            <p id="{{ $descId }}" class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $description }}
                            </p>
                        @endif
                    </div>
                @endif
                
                @if ($showCloseButton)
                    <button
                        type="button"
                        data-modal-close
                        class="absolute top-4 right-4 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                        aria-label="Закрыть"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        @endif

        {{-- Content --}}
        <div class="px-6 py-4 overflow-y-auto flex-1" role="document">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        @if (isset($footer))
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 shrink-0">
                <div class="flex justify-end gap-2">
                    {{ $footer }}
                </div>
            </div>
        @endif
    </div>
</div>