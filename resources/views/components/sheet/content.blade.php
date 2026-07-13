@props([
    'width' => 'w-full max-w-md',
    'height' => 'h-auto',
    'class' => null,
])

@aware([
    'side' => 'right'
])

@php    
    $panelClasses = 'absolute bg-white dark:bg-slate-800 shadow-xl flex flex-col';

    // Настройка анимаций в зависимости от стороны
    if ($side === 'right') {
        $panelClasses .= ' right-0 top-0 h-full ' . $width;
        $enter = 'translate-x-full';
        $leave = 'translate-x-full';
    } elseif ($side === 'left') {
        $panelClasses .= ' left-0 top-0 h-full ' . $width;
        $enter = '-translate-x-full';
        $leave = '-translate-x-full';
    } elseif ($side === 'top') {
        $panelClasses .= ' top-0 inset-x-0 ' . $height;
        $enter = '-translate-y-full';
        $leave = '-translate-y-full';
    } else { // bottom
        $panelClasses .= ' bottom-0 inset-x-0 ' . $height;
        $enter = 'translate-y-full';
        $leave = 'translate-y-full';
    }
@endphp

<template x-teleport="body">
    {{-- 
        Родитель без x-show! Чтобы не ломать анимацию детей.
        Управляем кликами: когда закрыто, parent не перехватывает клики (pointer-events-none) 
    --}}
    <div x-cloak class="fixed inset-0 z-50" :class="open ? '' : 'pointer-events-none'">
        
        {{-- Затемненный фон (Анимируется сам) --}}
        <div 
            x-show="open"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
            @click="open = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>

        {{-- Сама панель (Анимируется сама) --}}
        <div 
            x-show="open"
            role="dialog"
            aria-modal="true"
            class="{{ cn($panelClasses, $class) }}"
            @click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="{{ $enter }}"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="{{ $leave }}"
        >
            {{ $slot }}
        </div>
    </div>
</template>