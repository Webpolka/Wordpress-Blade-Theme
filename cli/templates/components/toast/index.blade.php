{{--
  Компонент: Toast
  Описание: Контейнер для всплывающих уведомлений (Toaster). Использует стор `toast` в Alpine.js.
  
  Стор `$store.toast`:
    - messages: array – массив уведомлений [{ id, message, type, title }]
  
  Методы:
    - show({ message, type = 'default', title = '', timeout = 4000 }) – добавить уведомление.
    - remove(id) – удалить уведомление по ID.
  
  Пример использования в Alpine:

        <x-button x-data @click="$store.toast.show({ message: 'Успешно!', type: 'success', timeout: 3000 })">
            Показать успех
        </x-button>


        <x-button x-data @click="$store.toast.show({ message: 'Ошибка сети', type: 'error', title: 'Ошибка', timeout: 5000 })">
            Показать ошибку
        </x-button>


        <x-button x-data @click="$store.toast.show({ message: 'Нажмите на крестик чтобы закрыть', timeout: 0 })">
            Вечное уведомление
        </x-button>

  
  Свойства (props) компонента:
    - position: string – позиция: 'top-left', 'top-center', 'top-right', 'bottom-left', 'bottom-center', 'bottom-right' (по умолчанию 'bottom-right').
  
  Пример размещения в лейауте:
    <x-toast position="top-right" />
--}}

@props([
    'position' => 'bottom-right',
])

@php
    $positions = [
        'top-left' => 'top-0 left-0',
        'top-center' => 'top-0 left-1/2 -translate-x-1/2',
        'top-right' => 'top-0 right-0',
        'bottom-left' => 'bottom-0 left-0',
        'bottom-center' => 'bottom-0 left-1/2 -translate-x-1/2',
        'bottom-right' => 'bottom-0 right-0',
    ];

    $positionClasses = $positions[$position] ?? $positions['bottom-right'];

    $containerClasses = cn(
        'fixed z-[9999] flex flex-col gap-2 p-4 max-w-md w-full pointer-events-none',
        $positionClasses,
        $attributes->get('class', ''),
    );
    $attributes = $attributes->except('class');
@endphp

<div {{ $attributes->merge(['class' => $containerClasses]) }} x-data aria-live="assertive">
    <template x-for="msg in $store.toast.messages" :key="msg.id">
        <div x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95 translate-x-4"
            class="pointer-events-auto w-full rounded-lg border p-4 shadow-lg backdrop-blur-sm flex items-center justify-between gap-3"
            :class="{
                'border-green-600 bg-green-600 text-white': msg.type === 'success',
                'border-destructive bg-destructive text-destructive-foreground': msg.type === 'error',
                'border-amber-500 bg-amber-500 text-white': msg.type === 'warning',
                'border-border bg-popover text-popover-foreground': msg.type === 'default' || !msg.type,
            }">
            <p class="text-sm font-medium" x-text="msg.message"></p>
            <button @click="$store.toast.remove(msg.id)"
                class="shrink-0 rounded-md p-1 opacity-70 hover:opacity-100 transition-opacity focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-transparent"
                aria-label="{{ __('Close', 'weblegko') }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>