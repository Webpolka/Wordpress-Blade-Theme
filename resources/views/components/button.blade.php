{{--
  Компонент: Button
  Описание: Универсальная кнопка или ссылка с оформлением в стиле shadcn/ui.
             Если передан проп `href` – рендерится тег <a>, иначе <button>.
             Для ссылок с `disabled` автоматически добавляются классы блокировки и атрибут `aria-disabled`.
  
  ============================================================
  СВОЙСТВА (props)
  ============================================================
    - variant: string – стиль кнопки. Доступные значения:
        * default (по умолчанию) – тёмная кнопка со светлым текстом (с адаптацией под тёмную тему)
        * primary – синяя заливка
        * secondary – оранжевая заливка
        * destructive – красная (опасное действие)
        * outline – обводка, прозрачный фон
        * ghost – прозрачная, с подсветкой при наведении
        * link – как ссылка, с подчёркиванием при наведении
    
    - size: string – размер. Варианты:
        * default (по умолчанию) – высота 10, отступы px-4
        * sm – малый (высота 9, отступы px-3)
        * lg – большой (высота 11, отступы px-8)
        * icon – квадратный, ширина и высота 10, для иконок
    
    - href: string – если указан, компонент становится ссылкой <a>
    - disabled: bool – блокирует кнопку (для <button> добавляет атрибут disabled, для <a> – классы и aria-disabled)
  
  ============================================================
  ДОПОЛНИТЕЛЬНО
  ============================================================
    - Любые стандартные HTML-атрибуты (id, style, data-*, type, и т.п.) передаются на корневой элемент.
    - Класс, переданный через `class`, будет добавлен к итоговым стилям через утилиту `cn()`.
    - По умолчанию type НЕ устанавливается (button будет submit в формах). Разработчик сам передаёт type через атрибуты.
    - Для ссылок автоматически добавляется role="button" для accessibility.

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  --------------------------------------------------------
  1. БАЗОВЫЕ ВАРИАНТЫ (variant)
  --------------------------------------------------------
  
  Все варианты кнопок:
    <div class="flex flex-wrap gap-2">
        <x-button variant="default">Default</x-button>
        <x-button variant="primary">Primary</x-button>
        <x-button variant="secondary">Secondary</x-button>
        <x-button variant="destructive">Destructive</x-button>
        <x-button variant="outline">Outline</x-button>
        <x-button variant="ghost">Ghost</x-button>
        <x-button variant="link">Link</x-button>
    </div>

  --------------------------------------------------------
  2. РАЗМЕРЫ (size)
  --------------------------------------------------------
  
  Все размеры:
    <div class="flex items-center gap-2">
        <x-button size="sm">Small</x-button>
        <x-button size="default">Default</x-button>
        <x-button size="lg">Large</x-button>
    </div>

  --------------------------------------------------------
  3. С ИКОНКАМИ
  --------------------------------------------------------
  
  Кнопка с иконкой слева:
    <x-button variant="primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Добавить
    </x-button>

  Кнопка с иконкой справа:
    <x-button variant="outline">
        Далее
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </x-button>

  Кнопка-иконка (только иконка):
    <x-button variant="outline" size="icon">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </x-button>

  Круглая кнопка-иконка:
    <x-button variant="primary" size="icon" class="rounded-full">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </x-button>

  --------------------------------------------------------
  4. СО СПИННЕРОМ (LOADING STATE)
  --------------------------------------------------------

  !!!!!!!! Всегда используй x-bind: когда значение — это Alpine переменная !!!!!!!!
  
  Базовая кнопка с loading:
    <x-button
        variant="primary"
        x-data="{ loading: false }"
        x-bind:disabled="loading"
        @click="loading = true; setTimeout(() => loading = false, 2000)"
    >
        <template x-if="loading">
            <x-spinner size="sm" />
        </template>
        <span x-show="!loading">Отправить</span>
        <span x-show="loading">Отправка...</span>
    </x-button>

  Кнопка с заменой текста:
    <x-button
        variant="primary"
        x-data="{ loading: false }"
        x-bind:disabled="loading"
        @click="loading = true; setTimeout(() => loading = false, 2000)"
    >
        <span x-text="loading ? 'Загрузка...' : 'Загрузить данные'"></span>
        <template x-if="loading">
            <x-spinner size="sm" />
        </template>
    </x-button>

  Кнопка-иконка с loading:
    <x-button
        variant="outline"
        size="icon"
        x-data="{ loading: false }"
        x-bind:disabled="loading"
        @click="loading = true; setTimeout(() => loading = false, 1500)"
    >
        <template x-if="loading">
            <x-spinner size="sm" color="current" />
        </template>
        <template x-if="!loading">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </template>
    </x-button>

  Все варианты кнопок со спиннером:
      <div class="flex flex-wrap gap-3">
        <x-button class="min-w-[8rem]" variant="primary" x-data="{ loading: false }" x-bind:disabled="loading"
            @click="loading = true; setTimeout(() => loading = false, 2000)">
            <template x-if="loading"><x-spinner size="sm" /></template>
            <span x-show="!loading">Primary</span>
        </x-button>
        
        <x-button class="min-w-[8rem]" variant="secondary" x-data="{ loading: false }" x-bind:disabled="loading"
            @click="loading = true; setTimeout(() => loading = false, 2000)">
            <template x-if="loading"><x-spinner size="sm" /></template>
            <span x-show="!loading">Secondary</span>
        </x-button>
        
        <x-button class="min-w-[8rem]" variant="destructive" x-data="{ loading: false }" x-bind:disabled="loading"
            @click="loading = true; setTimeout(() => loading = false, 2000)">
            <template x-if="loading"><x-spinner size="sm" /></template>
            <span x-show="!loading">Destructive</span>
        </x-button>
        
        <x-button class="min-w-[8rem]" variant="outline" x-data="{ loading: false }" x-bind:disabled="loading"
            @click="loading = true; setTimeout(() => loading = false, 2000)">
            <template x-if="loading"><x-spinner size="sm" color="current" /></template>
            <span x-show="!loading">Outline</span>
        </x-button>
        
        <x-button class="min-w-[8rem]" variant="ghost" x-data="{ loading: false }" x-bind:disabled="loading"
            @click="loading = true; setTimeout(() => loading = false, 2000)">
            <template x-if="loading"><x-spinner size="sm" color="current" /></template>
            <span x-show="!loading">Ghost</span>
        </x-button>
    </div>

  --------------------------------------------------------
  5. В ФОРМАХ
  --------------------------------------------------------
  
  Форма с submit кнопкой:
    <form method="POST" action="/submit" class="flex flex-col gap-4">
        @csrf
        <x-input name="email" label="Email" type="email" />
        <x-input name="password" label="Пароль" type="password" />
        
        <div class="flex gap-2">
            <x-button type="submit" variant="primary">
                Войти
            </x-button>
            <x-button type="button" variant="outline" @click="window.history.back()">
                Отмена
            </x-button>
        </div>
    </form>

  Форма с loading при отправке:
    <form
        x-data="{
            submitting: false,
            async submit() {
                this.submitting = true;
                await new Promise(r => setTimeout(r, 2000));
                this.submitting = false;
                alert('Отправлено!');
            }
        }"
        @submit.prevent="submit()"
        class="flex flex-col gap-4"
    >
        <x-input name="email" label="Email" type="email" />
        
        <x-button type="submit" variant="primary" x-bind:disabled="submitting">
            <template x-if="submitting">
                <x-spinner size="sm" />
            </template>
            <span x-text="submitting ? 'Отправка...' : 'Отправить'"></span>
        </x-button>
    </form>

  Форма с reset кнопкой:
    <form class="flex flex-col gap-4">
        <x-input name="name" label="Имя" />
        <x-input name="email" label="Email" type="email" />
        
        <div class="flex gap-2">
            <x-button type="submit" variant="primary">Сохранить</x-button>
            <x-button type="reset" variant="outline">Сбросить</x-button>
        </div>
    </form>

  --------------------------------------------------------
  6. ГРУППЫ КНОПОК
  --------------------------------------------------------
  
  Группа действий:
    <div class="flex gap-2">
        <x-button variant="primary">Сохранить</x-button>
        <x-button variant="outline">Отмена</x-button>
        <x-button variant="destructive">Удалить</x-button>
    </div>

  Группа с общим loading состоянием:
    <div x-data="{ saving: false }" class="flex gap-2">
        <x-button
            variant="primary"
            x-bind:disabled="saving"
            @click="saving = true; setTimeout(() => saving = false, 2000)"
        >
            <template x-if="saving">
                <x-spinner size="sm" />
            </template>
            <span x-show="!saving">Сохранить</span>
        </x-button>
        
        <x-button
            variant="outline"
            x-bind:disabled="saving"
            @click="alert('Отменено')"
        >
            Отмена
        </x-button>
    </div>

  Кнопки разных размеров:
    <div class="flex items-center gap-2">
        <x-button size="sm" variant="outline">Small</x-button>
        <x-button size="default" variant="outline">Default</x-button>
        <x-button size="lg" variant="outline">Large</x-button>
    </div>

  --------------------------------------------------------
  7. ССЫЛКИ (href)
  --------------------------------------------------------
  
  Базовая ссылка:
    <x-button href="https://example.com">Перейти на сайт</x-button>

  Ссылка в новой вкладке:
    <x-button href="https://example.com" target="_blank" rel="noopener noreferrer">
        Открыть в новой вкладке
    </x-button>

  Ссылка с вариантом:
    <x-button href="/dashboard" variant="primary">В панель управления</x-button>
    <x-button href="/docs" variant="outline">Документация</x-button>

  Disabled ссылка:
    <x-button href="/premium" variant="primary" :disabled="true">
        Премиум (недоступно)
    </x-button>

  --------------------------------------------------------
  8. DISABLED СОСТОЯНИЯ
  --------------------------------------------------------
  
  Disabled кнопка:
    <x-button variant="primary" :disabled="true">Недоступно</x-button>

  Disabled ссылка:
    <x-button href="/premium" variant="primary" :disabled="true">
        Премиум (недоступно)
    </x-button>

  Условный disabled:
    <x-button
        variant="primary"
        x-bind:disabled="!agreed"
        @click="alert('Отправлено!')"
    >
        Отправить
    </x-button>

  --------------------------------------------------------
  9. КАСТОМНЫЕ КЛАССЫ
  --------------------------------------------------------
  
  Круглая кнопка:
    <x-button variant="primary" class="rounded-full">
        Круглая кнопка
    </x-button>

  Полная ширина:
    <x-button variant="primary" class="w-full">
        На всю ширину
    </x-button>

  Кастомные отступы:
    <x-button variant="outline" class="px-6 py-3">
        Кастомные отступы
    </x-button>

  Тень:
    <x-button variant="primary" class="shadow-lg">
        С тенью
    </x-button>

  --------------------------------------------------------
  10. РЕАЛЬНЫЕ СЦЕНАРИИ
  --------------------------------------------------------
  
  Корзина товаров:
    <div class="flex gap-2">
        <x-button variant="primary" size="lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Оформить заказ
        </x-button>
        <x-button variant="outline" size="lg">
            Продолжить покупки
        </x-button>
    </div>

  Подтверждение удаления:
    <div class="flex gap-2">
        <x-button variant="destructive">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Удалить
        </x-button>
        <x-button variant="outline">Отмена</x-button>
    </div>

  Социальные кнопки:
    <div class="flex flex-col gap-2">
        <x-button variant="outline" class="w-full">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            Войти через Facebook
        </x-button>
        <x-button variant="outline" class="w-full">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Войти через Google
        </x-button>
    </div>

  ============================================================
  ВАЖНО: АТРИБУТ TYPE
  ============================================================
  По умолчанию компонент НЕ добавляет type="button".
  Это сделано намеренно, чтобы кнопка работала как submit в формах.

  Примеры:
    - Сабмит формы (по умолчанию):
      <x-button>Отправить</x-button>

    - Не сабмитит (нужен type="button"):
      <x-button type="button" @click="doSomething()">Действие</x-button>

    - Сброс формы:
      <x-button type="reset">Сбросить</x-button>

  ============================================================
  ПРИМЕЧАНИЯ
  ============================================================
    - Компонент автоматически определяет рендерить <button> или <a> на основе наличия href.
    - Для ссылок с disabled добавляются классы pointer-events-none opacity-50 и атрибут aria-disabled="true".
    - Все варианты адаптированы под тёмную тему через dark: классы.
    - Кнопка имеет focus-visible стили для keyboard navigation.
    - Disabled состояние блокирует pointer-events и уменьшает opacity.
--}}


@props([
    'variant'  => 'default',
    'size'     => 'default',
    'href'     => null,
    'disabled' => false,
])

@php
    // Базовые стили
    $baseClasses = cn(
        'no-underline inline-flex items-center justify-center gap-2 shrink-0',
        'rounded-md text-sm font-medium',
        'transition-colors',
        'cursor-pointer select-none whitespace-nowrap',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2',
        'disabled:pointer-events-none disabled:opacity-50',
    );

    // Варианты цветов
    $variants = [
        'primary'     => 'bg-blue-500 text-white hover:bg-blue-700',
        'secondary'   => 'bg-orange-500 text-white hover:bg-orange-700',
        'default'     => 'bg-slate-900 text-white hover:bg-slate-800 dark:bg-slate-50 dark:text-slate-900',
        'destructive' => 'bg-red-500 text-white hover:bg-red-600',
        'outline'     => 'border border-slate-200 bg-white hover:bg-slate-100 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800',
        'ghost'       => 'hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-slate-800 dark:hover:text-slate-100',
        'link'        => 'text-slate-900 underline-offset-4 hover:underline dark:text-slate-100',
    ];

    // Размеры
    $sizes = [
        'default' => 'h-10 px-4',
        'sm'      => 'h-9 px-3',
        'lg'      => 'h-11 px-8',
        'icon'    => 'h-10 w-10',
    ];

    // Финальные классы
    $userClasses = $attributes->get('class', '');
    $finalClasses = cn(
        $baseClasses,
        $variants[$variant] ?? $variants['default'],
        $sizes[$size] ?? $sizes['default'],
        $href && $disabled ? 'pointer-events-none opacity-50' : '',
        $userClasses,
    );

    $attributes = $attributes->except(['class']);

    if (!$href && $disabled) {
        $attributes = $attributes->merge(['disabled' => true]);
    }
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        role="button"
        class="{{ $finalClasses }}"
        @if ($disabled) aria-disabled="true" tabindex="-1" @endif
        {{ $attributes }}
    >
        {{ $slot }}
    </a>
@else    
    {{-- Разработчик сам передаёт type через атрибуты --}}
    <button class="{{ $finalClasses }}" {{ $attributes }}>
        {{ $slot }}
    </button>
@endif