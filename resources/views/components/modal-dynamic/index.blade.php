{{--
  Компонент: Modal Dynamic
  Описание: Универсальное модальное окно с динамическим контентом.
             Рендерится ОДИН раз в лейауте и переиспользуется через JS API.
             Визуально идентичен обычной модалке (x-modal).

  ============================================================
  ВАЖНО: ОГРАНИЧЕНИЯ
  ============================================================
  1. Используйте onclick вместо @click на <x-button>:
     ✅ <x-button onclick="window.modalManager.openDynamic({...})">
     ❌ <x-button @click="window.modalManager.openDynamic({...})">
     
     Причина: <x-button> — Blade компонент, @click не обрабатывается Alpine.

  2. Для многострочного HTML используйте обратные кавычки (template literals):
     ✅ content: `<p class='text-gray-600'>Текст</p>`
     ❌ content: '<p class=\'text-gray-600\'>Текст</p>'

  ============================================================
  JS API (через window.modalManager)
  ============================================================
  
  1. Открыть с параметрами:
    window.modalManager.openDynamic({
      title: 'Заголовок',
      content: '<p>HTML контент</p>',
      size: 'md',           // sm | md | lg | xl | 2xl | full
      closeOnOverlay: true, // закрывать по клику на overlay
      closeOnEscape: true,  // закрывать по Escape
      showCloseButton: true,// показывать крестик
      onClose: () => { ... } // колбэк при закрытии
    });

  2. Закрыть:
    window.modalManager.closeDynamic();

  3. Из контента (через data-атрибут):
    <button data-modal-close>Закрыть</button>

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Простое сообщение:
    <x-button onclick="window.modalManager.openDynamic({
      title: 'Информация',
      content: '<p>Это динамическая модалка!</p>'
    })">
      Показать сообщение
    </x-button>

  2. Сообщение через ModalContentFactory:
    <x-button onclick="window.modalManager.openDynamic({
      title: 'Успех',
      content: ModalContentFactory.message({
        text: 'Операция выполнена успешно!',
        variant: 'success'
      })
    })">
      Показать успех
    </x-button>

  3. Подтверждение с колбэком:
    <x-button variant="destructive" onclick="window.modalManager.openDynamic({
      title: 'Удалить запись?',
      content: ModalContentFactory.confirm({
        message: 'Это действие нельзя отменить.',
        confirmText: 'Удалить',
        cancelText: 'Отмена',
        variant: 'destructive',
        onConfirm: () => {
          console.log('Удаляем запись...');
        }
      }),
      size: 'sm'
    })">
      Удалить
    </x-button>

  4. Форма (через ModalContentFactory):
    <x-button onclick="window.modalManager.openDynamic({
      title: 'Редактировать профиль',
      content: ModalContentFactory.profileForm({ 
        name: 'Анна', 
        email: 'anna@mail.ru' 
      }),
      size: 'lg'
    })">
      Редактировать
    </x-button>

  5. С колбэком onClose:
    <x-button onclick="window.modalManager.openDynamic({
      title: 'Важное сообщение',
      content: '<p>Прочитайте и закройте.</p>',
      onClose: () => {
        console.log('Модалка закрыта');
        // Можно отправить аналитику, обновить UI и т.д.
      }
    })">
      Открыть с колбэком
    </x-button>

  6. Разные размеры:
    <x-button size="sm" onclick="window.modalManager.openDynamic({
      title: 'Маленькая',
      content: '<p>Компактная модалка.</p>',
      size: 'sm'
    })">Small</x-button>

    <x-button size="sm" onclick="window.modalManager.openDynamic({
      title: 'Большая',
      content: '<p>Для большого контента.</p>',
      size: 'lg'
    })">Large</x-button>

  7. Без крестика и закрытия по overlay:
    <x-button onclick="window.modalManager.openDynamic({
      title: 'Обязательное действие',
      content: `<p>Вы должны выполнить это действие.</p>
                <button data-modal-close class='mt-4 px-4 py-2 bg-blue-500 text-white rounded'>Понятно</button>`,
      showCloseButton: false,
      closeOnOverlay: false
    })">
      Обязательное
    </x-button>

  8. Произвольный HTML (через template literals):
    <x-button onclick="window.modalManager.openDynamic({
      title: 'Кастомный контент',
      content: `<div class='space-y-3'>
                  <p class='text-gray-600'>Первый абзац</p>
                  <p class='text-gray-600'>Второй абзац</p>
                  <button data-modal-close class='px-4 py-2 bg-blue-500 text-white rounded'>Закрыть</button>
                </div>`
    })">
      Кастомный
    </x-button>

    9. Простой Alert

       <x-button onclick="window.modalManager.openDynamic({
        title: 'Успех',
        content: window.ModalContentFactory.message({
                text: 'Операция выполнена!',
                variant: 'success'
            })
        })">
        Ок!
    </x-button>

  ============================================================
  MODAL CONTENT FACTORY
  ============================================================
  Для генерации типового контента используйте ModalContentFactory:

  1. Форма профиля:
    ModalContentFactory.profileForm({ name: 'Иван', email: 'ivan@mail.ru' })

  2. Подтверждение (объект с параметрами):
    ModalContentFactory.confirm({
      message: 'Вы уверены?',
      confirmText: 'Удалить',
      cancelText: 'Отмена',
      variant: 'destructive',  // primary | destructive | secondary
      onConfirm: () => { ... }
    })

  3. Простое сообщение (объект с параметрами):
    ModalContentFactory.message({
      text: 'Операция выполнена!',
      variant: 'success'  // info | success | warning | error
    })

  4. Произвольный HTML:
    ModalContentFactory.html('<div>...</div>')

  ============================================================
  РАЗМЕЩЕНИЕ В ЛЕЙАУТЕ
  ============================================================
  Добавьте компонент ОДИН раз в layouts/app.blade.php:

    <body>
        <div id="app">
            <!-- Ваш контент -->
        </div>

        <!-- Динамическая модалка -->
        <x-modal-dynamic />
    </body>

  ============================================================
  CSS АНИМАЦИИ
  ============================================================
  Добавьте в resources/css/app.css:

    /* Обычная модалка */
    [data-modal]:not(.hidden) {
      opacity: 0;
      transition: opacity 200ms ease-out;
    }

    [data-modal]:not(.hidden) [role="dialog"] {
      opacity: 0;
      transform: scale(0.95) translateY(10px);
      transition: opacity 200ms ease-out, transform 200ms ease-out;
    }

    /* Динамическая модалка */
    [data-modal-dynamic]:not(.hidden) {
      opacity: 0;
      transition: opacity 200ms ease-out;
    }

    [data-modal-dynamic]:not(.hidden) [role="dialog"] {
      opacity: 0;
      transform: scale(0.95) translateY(10px);
      transition: opacity 200ms ease-out, transform 200ms ease-out;
    }

    /* Общие классы для обеих модалок */
    .modal-overlay-visible {
      opacity: 1 !important;
    }

    .modal-dialog-visible {
      opacity: 1 !important;
      transform: scale(1) translateY(0) !important;
    }

  ============================================================
  ОТЛИЧИЯ ОТ ОБЫЧНОЙ МОДАЛКИ (x-modal)
  ============================================================
  x-modal (обычная):
    - Каждая модалка — отдельный DOM элемент
    - Контент в Blade (статичный)
    - Управление через data-modal-target
    - Для уникальных форм, сложного контента

  x-modal-dynamic (динамическая):
    - Одна модалка на всю страницу
    - Контент через JS (динамический)
    - Управление через window.modalManager.openDynamic()
    - Для простых сообщений, подтверждений, AJAX контента

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Работает на чистом JS, без Alpine
    - Визуально идентичен x-modal (те же классы, анимации)
    - Поддерживает все размеры (sm, md, lg, xl, 2xl, full)
    - Поддерживает колбэк onClose
    - Контент вставляется через innerHTML (будьте осторожны с XSS)
    - Для закрытия из контента используйте data-modal-close
    - Используйте onclick вместо @click на <x-button>
--}}

<div
    data-modal-dynamic
    data-close-on-overlay="true"
    data-close-on-escape="true"
    class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
>
    <div
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-dynamic-title"
        tabindex="-1"
        class="relative bg-white dark:bg-gray-900 rounded-lg shadow-xl w-full max-w-md max-h-[90vh] flex flex-col"
    >
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 shrink-0 relative">
            <div class="pr-8">
                <h3 id="modal-dynamic-title" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{-- Заголовок вставляется через JS --}}
                </h3>
            </div>
            
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
        </div>

        {{-- Content --}}
        <div class="px-6 py-4 overflow-y-auto flex-1" role="document">
            {{-- Контент вставляется через JS --}}
        </div>
    </div>
</div>