{{--
Ok !

==============================================================
 WP Components: Context Menu
==============================================================

Кастомное контекстное меню, которое вызывается по правому 
клику мыши (ПКМ). Идеально для админ-панелей, таблиц, 
менеджеров файлов или сложных карточек.

--------------------------------------------------------------
 1. ГЛАВНЫЕ ФИЧИ
--------------------------------------------------------------
 - Native feel: Полностью перехватывает ПКМ, убирая уродливое 
   меню браузера.
 - Viewport Protection: Меню измеряет свои размеры после 
   открытия. Если оно открылось у правого/нижнего края экрана, 
   оно автоматически сдвинется, чтобы не обрезаться.
 - Auto-close: Закрывается после клика по любому пункту, 
   клику вне зоны или нажатию Escape.

--------------------------------------------------------------
 2. ДОЧЕРНИЕ КОМПОНЕНТЫ
--------------------------------------------------------------
 <x-context-menu>             : Главный контейнер.
 <x-context-menu.trigger>     : Область, по которой кликают ПКМ.
 <x-context-menu.content>     : Само меню (выезжает в body).
 <x-context-menu.item>        : Пункт меню (кнопка).
   props:
     variant (string) : 'default' или 'destructive' (красный).
 <x-context-menu.separator>   : Линия-разделитель.

--------------------------------------------------------------
 3. ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
--------------------------------------------------------------

 // Меню управления товаром в таблице
 <x-context-menu>
     <x-context-menu.trigger>
         <tr class="hover:bg-gray-50 cursor-pointer">
             <td class="p-4">Товар №1</td>
         </tr>
     </x-context-menu.trigger>
     
     <x-context-menu.content>
         <x-context-menu.item>
             <svg class="w-4 h-4"><!-- Иконка --></svg>
             Редактировать
         </x-context-menu.item>
         <x-context-menu.item>
             Дублировать
         </x-context-menu.item>
         <x-context-menu.separator />
         <x-context-menu.item variant="destructive">
             Удалить навсегда
         </x-context-menu.item>
     </x-context-menu.content>
 </x-context-menu>

// Как сделать чтобы левой кнопккой срабатывал popover а правой context-menu ?

  <x-context-menu>
    <x-context-menu.trigger>      
        <x-popover>
            <x-popover.trigger>
                <div class="border p-4 cursor-pointer">
                    Кликни ЛКМ (откроется Popover) или ПКМ (откроется Context Menu)
                </div>
            </x-popover.trigger>
            
            <x-popover.content>
                <div class="p-4">Это Popover по левому клику!</div>
            </x-popover.content>
        </x-popover>
    </x-context-menu.trigger>

    <x-context-menu.content>
        <x-context-menu.item>Скопировать</x-context-menu.item>
        <x-context-menu.separator />
        <x-context-menu.item variant="destructive">Удалить</x-context-menu.item>
    </x-context-menu.content>
</x-context-menu>
==============================================================
--}}

@props([
    'class' => null,
])

<div 
    x-data="{ 
        open: false, 
        posX: 0, 
        posY: 0,
        adjX: 0, 
        adjY: 0,
        show(e) {
            e.preventDefault(); 
            this.posX = e.clientX;
            this.posY = e.clientY;
            this.adjX = e.clientX; // Сразу ставим базовые координаты
            this.adjY = e.clientY;
            this.open = true;
        },
        hide() { this.open = false; }
    }" 
    @keydown.escape.window="hide()"
    @scroll.window="hide()"
    class="{{ cn('relative inline-block', $class) }}"
>
    {{ $slot }}
</div>