@props([
    'class' => null,
])

<div class="{{ cn('flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700', $class) }}">
    <div class="flex-1">
        {{ $slot }}
    </div>
    <button 
        @click="open = false" 
        class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 transition-colors p-2 -mr-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-800" 
        aria-label="{{ __('Close', 'weblegko') }}"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>