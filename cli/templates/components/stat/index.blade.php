{{--
  ============================================================
  Компонент: Stat (Анимированный счетчик)
  Описание: Цифры, которые отсчитываются от 0 при появлении 
             в зоне видимости (viewport).
  ============================================================

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - value    (int)    : Конечное число (например 5000). Обязательно.
    - label    (string) : Подпись под цифрой (например "Клиентов").
    - prefix   (string) : Текст до числа (например "$").
    - suffix   (string) : Текст после числа (например "+").
    - duration (int)    : Длительность анимации в мс. По умолчанию: 2000.
    - class    (string) : Доп. классы.

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  1. Внутри секции лендинга (сетка из 4 штук):
   <x-section variant="muted" :container="false" class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <x-stat :value="10" suffix=" лет" label="На рынке" />
        <x-stat :value="5000" suffix="+" label="Клиентов" />
        <x-stat :value="120" suffix="+" label="Специалистов" />
        <x-stat :value="99" suffix="%" label="Успешных кейсов" />
    </x-section>
--}}

@props([
    'value'    => 0,
    'label'    => null,
    'prefix'   => '',
    'suffix'   => '',
    'duration' => 2000,
    'class'    => null,
])

@php
    $wrapperClasses = cn(
        'flex flex-col items-center text-center',
        $class
    );
@endphp

<div
    x-data="stat({ target: {{ (int) $value }}, duration: {{ (int) $duration }} })"
    class="{{ $wrapperClasses }}"
>
    {{-- Само число с префиксом/суффиксом --}}
    <div class="text-4xl md:text-5xl font-extrabold tracking-tight text-primary tabular-nums">
        {{ $prefix }}<span x-text="current"></span>{{ $suffix }}
    </div>

    {{-- Подпись --}}
    @if ($label)
        <div class="mt-2 text-sm font-medium text-muted-foreground uppercase tracking-wider">
            {{ $label }}
        </div>
    @endif
</div>