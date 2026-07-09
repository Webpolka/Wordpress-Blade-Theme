{{--
  Компонент: Tooltip Fixed
  Описание: Всплывающая подсказка с fixed positioning.
             Не обрезается в overflow контейнерах.
             Работает через data-атрибуты.

  ============================================================
  АТРИБУТЫ (data-*)
  ============================================================
    - data-tooltip           : string – текст подсказки (ОБЯЗАТЕЛЬНО)
    - data-tooltip-position  : string – позиция: 'top' (по умолчанию) | 'bottom' | 'left' | 'right'
    - data-tooltip-theme     : string – тема: 'dark' (по умолчанию) | 'light'
    - data-tooltip-color     : string – кастомные Tailwind классы (переопределяет theme)
    - data-tooltip-distance  : float – расстояние от элемента в rem (по умолчанию 0.5)
    - data-tooltip-arrow     : bool – показывать стрелку (по умолчанию true, 'false' чтобы скрыть)
    - data-tooltip-delay     : int – задержка появления в мс (по умолчанию 200)

  ============================================================
  ОСОБЕННОСТИ
  ============================================================
    - Fixed positioning — не обрезается в overflow контейнерах
    - Скрывается при скролле (чтобы не "съезжал")
    - Восстанавливается после остановки скролла (если курсор на триггере)
    - Все размеры в rem (адаптивность под font-size)
    - Автоматическое вычисление среднего цвета стрелки для градиентов
    - Один tooltip на страницу (не создаёт дубликаты)

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Базовый tooltip:
    <x-button data-tooltip="Текст подсказки">Hover me</x-button>

  2. Разные позиции:
    <div class="flex gap-4">
        <x-button size="sm" data-tooltip="Сверху" data-tooltip-position="top">Top</x-button>
        <x-button size="sm" data-tooltip="Снизу" data-tooltip-position="bottom">Bottom</x-button>
        <x-button size="sm" data-tooltip="Слева" data-tooltip-position="left">Left</x-button>
        <x-button size="sm" data-tooltip="Справа" data-tooltip-position="right">Right</x-button>
    </div>

  3. Светлая тема:
    <x-button data-tooltip="Светлая подсказка" data-tooltip-theme="light">Hover</x-button>

  4. Кастомные цвета:
    <x-button data-tooltip="Синий" data-tooltip-color="bg-blue-500 text-white">Blue</x-button>
    <x-button data-tooltip="Зелёный" data-tooltip-color="bg-green-500 text-white">Green</x-button>
    <x-button data-tooltip="Красный" data-tooltip-color="bg-red-500 text-white">Red</x-button>

  5. Градиенты (стрелка автоматически вычисляет средний цвет):
    <x-button data-tooltip="Градиент синий→фиолетовый" 
              data-tooltip-color="bg-gradient-to-r from-blue-500 to-purple-500 text-white">
      Gradient
    </x-button>

    <x-button data-tooltip="Градиент красный→жёлтый" 
              data-tooltip-color="bg-gradient-to-r from-red-500 to-yellow-500 text-white">
      Warm
    </x-button>

    <x-button data-tooltip="Градиент зелёный→голубой" 
              data-tooltip-color="bg-gradient-to-r from-green-500 to-cyan-500 text-white">
      Fresh
    </x-button>

  6. Без стрелки:
    <x-button data-tooltip="Без стрелки" data-tooltip-arrow="false">Hover</x-button>

  7. Разные расстояния (в rem):
    <div class="flex gap-4">
        <x-button size="sm" data-tooltip="Близко (0.25rem)" data-tooltip-distance="0.25">0.25rem</x-button>
        <x-button size="sm" data-tooltip="Стандарт (0.5rem)" data-tooltip-distance="0.5">0.5rem</x-button>
        <x-button size="sm" data-tooltip="Средне (1rem)" data-tooltip-distance="1">1rem</x-button>
        <x-button size="sm" data-tooltip="Далеко (1.5rem)" data-tooltip-distance="1.5">1.5rem</x-button>
    </div>

  8. С задержкой:
    <x-button data-tooltip="Появится через 500мс" data-tooltip-delay="500">Hover</x-button>

  9. Мгновенное появление:
    <x-button data-tooltip="Мгновенно" data-tooltip-delay="0">Hover</x-button>

  10. В overflow контейнере (НЕ обрезается!):
    <div class="overflow-hidden h-64 border">
        <x-button data-tooltip="Не обрежется!" data-tooltip-position="bottom">Hover</x-button>
    </div>

  11. В таблице со скроллом:
    <div class="overflow-x-auto">
        <table class="w-full">
            <tbody>
                <tr>
                    <td>
                        <span data-tooltip="Длинный текст подсказки" class="truncate max-w-[150px] inline-block">
                            Длинный текст...
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

  12. В drawer/modal:
    <x-mobile-drawer title="Меню">
        <nav>
            <a href="#" data-tooltip="Перейти на главную" data-tooltip-position="right">Главная</a>
            <a href="#" data-tooltip="Настройки профиля" data-tooltip-position="right">Настройки</a>
        </nav>
    </x-mobile-drawer>

  13. Комбинация всех настроек:
    <x-button 
        data-tooltip="Полная настройка" 
        data-tooltip-position="bottom"
        data-tooltip-distance="1"
        data-tooltip-color="bg-gradient-to-r from-indigo-500 to-purple-500 text-white"
        data-tooltip-delay="300"
    >
        Hover me
    </x-button>

  ============================================================
  ОТЛИЧИЯ ОТ ОБЫЧНОГО TOOLTIP (x-tooltip)
  ============================================================
  x-tooltip (обычный):
    - Absolute positioning
    - Внутри компонента
    - Может обрезаться в overflow
    - Alpine.js компонент
    - Лучше для простых случаев

  x-tooltip-fixed (этот):
    - Fixed positioning
    - Создаётся в body
    - НЕ обрезается в overflow
    - Чистый JS
    - Лучше для таблиц, drawer, modal
    - Скрывается при скролле
    - Автоматический средний цвет для градиентов

  ============================================================
  КАК РАБОТАЕТ СКРОЛЛ
  ============================================================
  1. Пользователь hover'ит на элемент → tooltip появляется
  2. Пользователь начинает скроллить → tooltip СКРЫВАЕТСЯ (чтобы не съезжал)
  3. Пользователь останавливает скролл → проверяется позиция курсора
  4. Если курсор всё ещё на триггере → tooltip ВОССТАНАВЛИВАЕТСЯ
  5. Если курсор ушёл → tooltip остаётся скрытым

  Это стандартное поведение — GitHub, Twitter и другие крупные сайты делают так же.

  ============================================================
  КАК РАБОТАЕТ СРЕДНИЙ ЦВЕТ ДЛЯ ГРАДИЕНТОВ
  ============================================================
  Для градиентов типа: bg-gradient-to-r from-blue-500 to-purple-500
  
  1. Парсим from-blue-500 → #3b82f6
  2. Парсим to-purple-500 → #a855f7
  3. Вычисляем средний цвет → #726be9
  4. Применяем к стрелке через inline style: background: #726be9

  Результат: стрелка идеально соответствует середине градиента!

  Поддерживаются все Tailwind цвета:
  - slate, gray, zinc, neutral, stone
  - red, orange, amber, yellow, lime, green, emerald, teal, cyan
  - sky, blue, indigo, violet, purple, fuchsia, pink, rose

  ============================================================
  ACCESSIBILITY
  ============================================================
    - role="tooltip" на контейнере подсказки
    - Показывается при hover (мышь)
    - Показывается при focus (клавиатура)
    - Скрывается при уходе курсора/потере фокуса

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Работает на чистом JS, без Alpine
    - Fixed positioning (не обрезается)
    - Скрывается при скролле (не съезжает)
    - Восстанавливается после скролла (если курсор на триггере)
    - Все размеры в rem (адаптивность)
    - Автоматический средний цвет стрелки для градиентов
    - Один tooltip на страницу (не создаёт дубликаты)
    - Поддерживает все настройки через data-атрибуты
    - Accessibility (role="tooltip")
    - Плавная анимация появления/искрытия (opacity fade)
--}}

{{-- Компонент не рендерит ничего, просто добавляет data-атрибуты к элементу --}}
{{ $slot }}