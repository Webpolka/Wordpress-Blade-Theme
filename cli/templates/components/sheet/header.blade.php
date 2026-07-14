@props([
    'class' => null,
])

<div class="{{ cn('flex items-center justify-between p-4 border-b border-border', $class) }}">
    <div class="flex-1">
        {{ $slot }}
    </div>
    <button 
        @click="open = false" 
        class="text-muted-foreground hover:text-foreground transition-colors p-2 -mr-2 rounded-md hover:bg-accent focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background" 
        aria-label="{{ __('Close', 'weblegko') }}"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>