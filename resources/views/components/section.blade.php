{{--
  ============================================================
  Компонент: Section (Секция лендинга)
  Описание: Умная обертка для секций страницы. Задает 
             вертикальные отступы, центрирует контент и 
             управляет фоном.
  ============================================================

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - as             (string)  : HTML-тег (section, div, footer). По умолчанию: 'section'.
    - size           (string)  : Размер вертикальных отступов ('sm', 'md', 'lg', 'xl'). По умолчанию: 'lg'.
    - variant        (string)  : Цвет фона ('default', 'muted', 'primary', 'dark'). По умолчанию: 'default'.
    - container      (bool)    : Центрировать ли контент в max-w-7xl. По умолчанию: true.
    - class          (string)  : Доп. классы для секции.
    - containerClass (string)  : Доп. классы для внутреннего контейнера.

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Стандартная секция (белый фон, большие отступы):
    <x-section>
        <h1>Добро пожаловать!</h1>
    </x-section>

  2. Серый фон (чередование секций на лендинге):
    <x-section variant="muted">
        <h2>Наши преимущества</h2>
    </x-section>

  3. Темная секция (инверсия, для CTA-блоков):
    <x-section variant="dark" size="xl">
        <h2 class="text-white">Готовы начать?</h2>
    </x-section>

  4. Полноширинная секция (без контейнера внутри):
    <x-section :container="false">
        <img src="wide-banner.jpg" class="w-full">
    </x-section>
--}}

@props([
    'as'             => 'section',
    'size'           => 'lg',
    'variant'        => 'default',
    'container'      => true,
    'class'          => null,
    'containerClass' => null,
])

@php
    // Маппинг отступов
    $sizes = [
        'sm' => 'py-8 md:py-12',
        'md' => 'py-12 md:py-16',
        'lg' => 'py-16 md:py-24',
        'xl' => 'py-24 md:py-32',
    ];
    $currentSize = $sizes[$size] ?? $sizes['lg'];

    // Маппинг фонов (Design System)
    $variants = [
        'default' => 'bg-background text-foreground',
        'muted'   => 'bg-muted text-foreground',           // Аккуратный серый фон (slate-100 / slate-800)
        'primary' => 'bg-primary text-primary-foreground', // Брендовый синий фон
        'dark'    => 'bg-foreground text-background',      // Инверсия (темный фон на светлом сайте)
    ];
    $currentVariant = $variants[$variant] ?? $variants['default'];

    $sectionClasses = cn(
        'relative w-full',
        $currentSize,
        $currentVariant,
        $class
    );

    $containerClasses = cn(
        'mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8',
        $containerClass
    );
@endphp

<{{ $as }} class="{{ $sectionClasses }}">
    @if ($container)
        <div class="{{ $containerClasses }}">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</{{ $as }}>