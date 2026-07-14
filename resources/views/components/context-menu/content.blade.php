@props([
    'class' => null,
])

<template x-teleport="body">
    <div 
        x-show="open"
        x-cloak
        @click.away="hide()"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        
        {{-- Design System: bg-popover text-popover-foreground border-border --}}
        class="fixed z-50 min-w-[12rem] bg-popover text-popover-foreground rounded-lg border border-border shadow-xl p-1.5 outline-none"
        
        :style="`top: ${adjY}px; left: ${adjX}px`"
        
        x-effect="            
            const currentX = posX;
            const currentY = posY;
            
            if (open) {
                $nextTick(() => {
                    const rect = $el.getBoundingClientRect();
                    let x = currentX;
                    let y = currentY;

                    if (x + rect.width > window.innerWidth - 25) {
                        x = window.innerWidth - rect.width - 25;
                    }
                    if (y + rect.height > window.innerHeight - 25) {
                        y = window.innerHeight - rect.height - 25;
                    }
                    if (x < 25) x = 25;
                    if (y < 25) y = 25;

                    adjX = x;
                    adjY = y;
                });
            }
        "
    >
        <div @click="hide()" class="{{ cn($class) }}">
            {{ $slot }}
        </div>
    </div>
</template>