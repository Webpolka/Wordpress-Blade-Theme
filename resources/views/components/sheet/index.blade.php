{{--
==============================================================
 WP Components: Sheet (Боковая панель)
==============================================================

Выезжающая панель (Side Panel), которая пришла на смену 
тяжелым модальным окнам. Идеальна для фильтров каталога, 
быстрого чекаута корзины или меню личного кабинета.

--------------------------------------------------------------
 1. ГЛАВНЫЕ ФИЧИ
--------------------------------------------------------------
 - 4 стороны: Панель может выезжать слева, справа, сверху 
   или снизу (пропс side).
 - x-teleport: Панель физически рендерится в <body>, обходя 
   любые баги с z-index и overflow:hidden.
 - Scroll Lock: При открытии панели скролл страницы фона 
   блокируется.
 - Умное закрытие: Закрывается по Esc, клику на затемненный 
   фон или крестику.

--------------------------------------------------------------
 2. ДОЧЕРНИЕ КОМПОНЕНТЫ
--------------------------------------------------------------
 <x-sheet>               : Главный контейнер (хранит open).
   props:
     side (string) : Сторона выезда (left, right, top, bottom).
                     По умолчанию: 'right'.

 <x-sheet.trigger>       : Кнопка открытия.
 <x-sheet.content>       : Обертка самой панели.
   props:
     width (string)  : Ширина (для left/right). 
                       По умолчанию: 'w-full max-w-md'.
     height (string) : Высота (для top/bottom). 
                       По умолчанию: 'h-auto'.
 <x-sheet.header>        : Шапка с авто-крестиком.
 <x-sheet.title>         : Заголовок в шапке.
 <x-sheet.footer>        : Подвал панели (прижимается к низу).

--------------------------------------------------------------
 3. ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
--------------------------------------------------------------

 // 1. Панель справа (Фильтры)
 <x-sheet side="right">
     <x-sheet.trigger>
         <x-button>Фильтры</x-button>          
     </x-sheet.trigger>
     <x-sheet.content width="w-96">
         <x-sheet.header>
             <x-sheet.title>Фильтры</x-sheet.title>
         </x-sheet.header>
         <div class="p-4 flex-1 overflow-y-auto">
             Тут чекбоксы и слайдеры...
         </div>
         <x-sheet.footer>
            <x-button class="w-full" >Применить</x-button>              
         </x-sheet.footer>
     </x-sheet.content>
 </x-sheet>

 // 2. Панель снизу (для мобилок)
 <x-sheet side="bottom">
     <x-sheet.trigger>
        <x-button>Сортировка</x-button>         
     </x-sheet.trigger>
     <x-sheet.content height="h-1/2">
         <x-sheet.header>
             <x-sheet.title>Сортировать по</x-sheet.title>
         </x-sheet.header>
         <div class="p-4">
             Список сортировок...
         </div>
     </x-sheet.content>
 </x-sheet>

==============================================================
--}}

@props([
    'side' => 'right', // left, right, top, bottom
])

<div 
    x-data="{ open: false }" 
    @keydown.escape.window="open = false"
    {{-- МАГИЯ: Блокируем скролл body когда панель открыта --}}
    x-effect="document.body.style.overflow = open ? 'hidden' : ''"
>
    {{ $slot }}
</div>