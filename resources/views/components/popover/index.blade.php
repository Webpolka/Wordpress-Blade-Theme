{{--
==============================================================
 WP Components: Popover
==============================================================

Всплывающее окно, которое появляется по клику на триггер.
В отличие от Modal, не затемняет фон и висит прямо в потоке
возле кнопки. Идеально для мини-корзин, меню пользователя
или быстрых фильтров.

Построен на Alpine.js (встроенный x-data, не требует script.js).

--------------------------------------------------------------
 1. ДОЧЕРНИЕ КОМПОНЕНТЫ
--------------------------------------------------------------
 <x-popover>           : Главный контейнер (хранит состояние open).
 <x-popover.trigger>   : Элемент, открывающий окно.
   props:
     as    (string) : Тег триггера (button, div, a). По умолчанию: 'button'.
 <x-popover.content>   : Панель с контентом.
   props:
     placement (string) : Позиция появления. По умолчанию: 'bottom-start'.
                          Варианты: bottom-start, bottom-end, top-start, 
                          top-end, left, right.
     width     (string) : Ширина панели (Tailwind класс). По умолчанию: 'w-64'.
                          Варианты: w-72, w-80, w-96, max-w-sm и т.д.

--------------------------------------------------------------
 2. ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
--------------------------------------------------------------

 // 1. Меню пользователя (выравнивание по правому краю)
 <x-popover>
     <x-popover.trigger>
         <img src="/avatar.jpg" class="w-8 h-8 rounded-full">
     </x-popover.trigger>
     
     <x-popover.content placement="bottom-end" width="w-56">
         <nav class="p-2 flex flex-col gap-1">
             <a href="/profile" class="px-3 py-2 rounded hover:bg-gray-100">Профиль</a>
             <a href="/logout" class="px-3 py-2 rounded hover:bg-gray-100">Выйти</a>
         </nav>
     </x-popover.content>
 </x-popover>

 // 2. Мини-корзина (широкая панель)
 <x-popover>
     <x-popover.trigger>
         <x-button> Корзина </x-button>
     </x-popover.trigger>
     
     <x-popover.content placement="bottom-end" width="w-80">
         <div class="p-4">
             <p class="font-bold mb-2">Товары:</p>
             <!-- Список товаров -->
             <x-button class="w-full mt-4">Оформить</x-button>
         </div>
     </x-popover.content>
 </x-popover>

 // 3. Всплывающая форма авторизации
 <x-popover>
     <x-popover.trigger as="a" class="text-blue-600 cursor-pointer">
         <x-button class="w-full mt-4">Войти</x-button>
     </x-popover.trigger>
     
     <x-popover.content placement="bottom-start" width="w-72">
         <form class="p-4 flex flex-col gap-3">
             <x-input type="email" showError="false" placeholder="Email" class="border p-2 rounded"/>
             <x-input type="password" showError="false" placeholder="Пароль" class="border p-2 rounded"/>
             <x-button type="submit" class="bg-blue-600 text-white p-2 rounded">Войти</x-button>
         </form>
     </x-popover.content>
 </x-popover>
--}}

@props([
    'class' => null,
])

<div 
    x-data="{ open: false }" 
    @keydown.escape.window="open = false"
    @click.outside="open = false"
    class="relative inline-block {{ $class }}"
>
    {{ $slot }}
</div>