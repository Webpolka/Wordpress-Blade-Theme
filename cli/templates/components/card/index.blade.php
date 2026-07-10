{{-- 

==============================================================
 WP Components: Card
==============================================================

Базовый компонент карточки. Построен по принципу композиции 
(состоит из мелких независимых блоков, как в shadcn/ui).

--------------------------------------------------------------
 1. ДОЧЕРНИЕ КОМПОНЕНТЫ
--------------------------------------------------------------
 <x-card>               : Главная обертка.
   props:
     as    (string) : HTML-тег (div, article). По умолчанию: 'div'.
     hover (bool)   : Эффект подъема при наведении. По умолчанию: false.
     class (string) : Доп. классы.

 <x-card.header>        : Верхний блок (с отступами).
 <x-card.title>         : Заголовок.
   props:
     as    (string) : Тег заголовка (h2, h3). По умолчанию: 'h3'.
 <x-card.description>   : Поясняющий текст (серым цветом).
 <x-card.content>       : Основной контент.
 <x-card.footer>        : Нижний блок (для кнопок/ссылок).
   props:
     align (string) : Выравнивание (start, end, between). По умолчанию: 'start'.

--------------------------------------------------------------
 2. ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
--------------------------------------------------------------

 // 1. Простая карточка
 <x-card>
     <x-card.header>
         <x-card.title>Заголовок</x-card.title>
         <x-card.description>Описание</x-card.description>
     </x-card.header>
     <x-card.content>
         Текст карточки...
     </x-card.content>
 </x-card>

 // 2. Карточка-статья с ховером и кнопкой
 <x-card as="article" :hover="true" class="max-w-sm">
     <x-card.header>
         <x-card.title as="h2">Блог</x-card.title>
          <x-card.description>Описание</x-card.description>
     </x-card.header>
     <x-card.content>
         Анонс новой статьи...
     </x-card.content>
     <x-card.footer align="end">
         <x-button>Читать</x-button>
     </x-card.footer>
 </x-card>

 --}}
 

@props([
    'as'    => 'div',
    'hover' => false,
    'class' => null,
])

@php
    $classes = cn(
        'rounded-xl border border-gray-200 bg-white text-gray-950 shadow-sm dark:border-gray-800 dark:bg-gray-950 dark:text-gray-50',
        $hover ? 'transition-all duration-300 hover:shadow-lg hover:-translate-y-1' : '',
        $class
    );
@endphp

<{{ $as }} class="{{ $classes }}" {{ $attributes }}>
    {{ $slot }}
</{{ $as }}>