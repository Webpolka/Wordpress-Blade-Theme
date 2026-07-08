{{--
  Component: Container
  Description: Центрированный контейнер с ограничением максимальной ширины (90rem) и адаптивными отступами.
               Минимальная ширина — 20rem. Отступы: px-4 на мобильных, px-8 на sm, px-16 на xl.
               Изменяем так как вам необходмо под ваш проект
  
  Props:
    - Все стандартные HTML-атрибуты передаются на корневой <div> через $attributes.
    - class: строка — добавляется к базовым классам.

  Пример:
    <x-container class="bg-gray-100">
        <p>Содержимое контейнера</p>
    </x-container>
    <x-container id="main" style="border: 1px solid red;">
        Контент с дополнительными атрибутами
    </x-container>
--}}

@php
    $classes = cn('w-full max-w-[90rem] min-w-[20rem] mx-auto px-4 sm:px-8 xl:px-16', $attributes->get('class', ''));
    $attributes = $attributes->except('class');
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
