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
            <div class="{{ cn('bg-background text-foreground hover:bg-accent hover:text-accent-foreground',
            'w-16 h-16 rounded-full flex items-center justify-center shadow-lg transition-transform group-hover:scale-110') }}">                
                <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                </svg>
            </div>
        </div>
    @endif
</{{ $as }}>