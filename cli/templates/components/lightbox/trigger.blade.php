@props([
    'index' => 0,
    'as' => 'div',
    'class' => null,
    'play' => false,
])

<{{ $as }} 
    @click="open({{ $index }})"
    class="{{ cn('relative cursor-pointer inline-block group', $class) }}"
>
    {{ $slot }}
    
    @if($play)
        <div class="absolute inset-0 flex items-center justify-center bg-black/30 rounded-lg transition-all group-hover:bg-black/40">
            <div class="{{ cn("bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700",
            "text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-100",
            "w-16 h-16 rounded-full flex items-center justify-center shadow-lg transition-transform group-hover:scale-110") }}">                
                <svg class="w-8 h-8  ml-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                </svg>
            </div>
        </div>
    @endif
</{{ $as }}>