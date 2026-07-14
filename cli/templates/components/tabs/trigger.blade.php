@props([
    'value' => null,
    'class' => null,
])

@php
    // Генерируем безопасный ID из значения (на случай пробелов или спецсимволов)
    $tabId = 'tab-' . Illuminate\Support\Str::slug($value);
    $panelId = 'panel-' . Illuminate\Support\Str::slug($value);
@endphp

<button
    type="button"
    role="tab"
    id="{{ $tabId }}"
    aria-controls="{{ $panelId }}"
    
    {{-- МАГИЯ: Если родитель не имеет default, открываем первую же кнопку --}}
    x-init="if(!activeTab) activeTab = '{{ $value }}'"
    @click="activeTab = '{{ $value }}'"
    
    :aria-selected="activeTab === '{{ $value }}'"
    
    {{-- SEO & A11y: Активный таб доступен по Tab, неактивные пропускаются (roving tabindex) --}}
    :tabindex="activeTab === '{{ $value }}' ? 0 : -1"
    
    {{-- Навигация стрелками для скринридеров --}}
    @keydown.arrow-right.prevent="$el.nextElementSibling?.focus() || $el.parentElement.firstElementChild.focus()"
    @keydown.arrow-left.prevent="$el.previousElementSibling?.focus() || $el.parentElement.lastElementChild.focus()"
    
    class="{{ cn('inline-flex items-center justify-center whitespace-nowrap py-3 text-sm font-medium border-b-2 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background rounded-t-md', $class) }}"
    :class="activeTab === '{{ $value }}' 
        ? 'border-primary text-primary' 
        : 'border-transparent text-muted-foreground hover:text-foreground'"
>
    {{ $slot }}
</button>