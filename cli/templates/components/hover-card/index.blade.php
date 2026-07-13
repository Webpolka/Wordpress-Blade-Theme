{{--
Ok !

==============================================================
 WP Components: HoverCard
==============================================================

Всплывающая карточка, которая появляется при наведении мыши на 
триггер. В отличие от Tooltip, внутри может быть сложный HTML 
(картинки, кнопки, формы).

--------------------------------------------------------------
 1. ГЛАВНЫЕ ФИЧИ
--------------------------------------------------------------
 - Hover Bridge: Умная задержка (200мс) позволяет пользователю 
   перевести курсор с триггера на саму плашку, чтобы кликнуть 
   по ссылке внутри, не закрывая её.
 - A11y: Поддерживает появление не только при наведении мыши, 
   но и при фокусе с клавиатуры (Tab).
 - Clean UI: Плавная анимация появления (opacity + slide down).

--------------------------------------------------------------
 2. ДОЧЕРНИЕ КОМПОНЕНТЫ
--------------------------------------------------------------
 <x-hover-card>             : Главный контейнер.
 <x-hover-card.trigger>     : Элемент-триггер.
   props:
     as (string) : Тег (div, a, span). По умолчанию: 'div'.
 <x-hover-card.content>     : Плашка с контентом.
   props:
     placement (string) : Позиция (bottom-start, bottom-end).
                          По умолчанию: 'bottom-start'.
     width (string)     : Ширина (Tailwind класс). 
                          По умолчанию: 'w-80'.

--------------------------------------------------------------
 3. ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
--------------------------------------------------------------

 // 1. Карточка пользователя (блог, комментарии)
<x-hover-card>
     <x-hover-card.trigger as="a" href="/author" class="text-blue-600 font-medium">
         Иван Иванов
     </x-hover-card.trigger>
     
     <x-hover-card.content width="w-72">
         <div class="p-4 flex gap-3">
             <x-avatar src="/img.jpg" alt="Иван Иванов" />
             <div>
                 <h3 class="font-bold">Иван Иванов</h3>
                 <p class="text-sm">Разработчик и писатель.</p>
                 <x-button>Читать блог</x-button>
             </div>
         </div>
     </x-hover-card.content>
 </x-hover-card>

 // 2. Выравнивание по правому краю (для элементов в правой части хедера)
 <x-hover-card>
     <x-hover-card.trigger>
         <img src="/avatar.jpg" class="w-8 h-8 rounded-full">
     </x-hover-card.trigger>
     <x-hover-card.content placement="bottom-end">
         ...
     </x-hover-card.content>
 </x-hover-card>
==============================================================
--}}

@props([
    'class' => null,
])

<div 
    x-data="{ 
        open: false, 
        timeout: null, 
        show() { clearTimeout(this.timeout); this.open = true; }, 
        hide() { this.timeout = setTimeout(() => this.open = false, 200); } 
    }" 
    class="{{ cn('relative inline-block', $class) }}"
>
    {{ $slot }}
</div>