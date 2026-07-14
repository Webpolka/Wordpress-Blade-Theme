@props([
    'class' => null,
])

<template x-teleport="body">
    <div 
        x-show="isOpen"
        x-cloak
        class="fixed inset-0 z-[9999] bg-black/95 flex items-center justify-center p-4"
        @click.self="close()"
        @mousemove.window="doDrag($event)"
        @mouseup.window="stopDrag()"
        x-transition.opacity
    >
        <button @click="close()" class="absolute top-4 right-4 md:right-8 z-50 w-12 h-12 bg-background/80 backdrop-blur border border-border rounded-full flex items-center justify-center text-muted-foreground hover:bg-background hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-black/95">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

         <div x-show="items.length > 1" class="absolute top-4 left-1/2 -translate-x-1/2 z-50 text-foreground text-sm bg-foreground/10 backdrop-blur px-4 py-1.5 rounded-full">
            <span x-text="activeIndex + 1"></span> / <span x-text="items.length"></span>
        </div>

        <div class="swiper lightbox-swiper w-full h-full" @click.self="close()">
            <div class="swiper-wrapper items-center">
                
                <template x-for="(item, index) in items" :key="index">
                     <div class="swiper-slide flex items-center justify-center flex-col gap-4 overflow-hidden transition-opacity duration-200" 
                         :class="isDragging && activeIndex !== index ? 'opacity-0' : 'opacity-100'">
                                                                                        
                      {{-- 1. Картинка (С кастомным зумом) --}}
                        <template x-if="item.type === 'image'">
                            <img 
                                :src="item.src" 
                                class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl select-none transition-transform duration-200" 
                                :class="zoom > 1 ? (isDragging ? 'cursor-grabbing transition-none' : 'cursor-grab') : (zoomEnabled ? 'cursor-zoom-in' : 'cursor-default')"                              
                                :style="`transform: translate(${panX}px, ${panY}px) scale(${zoom})`"
                                @dblclick.prevent="toggleZoom()"
                                @mousedown.stop.prevent="startDrag($event)"
                                alt="Lightbox Image"
                                draggable="false"
                            >
                        </template>                        

                        {{-- 2. iframe (YouTube, Vimeo, и т.д.) + Скелетон --}}                                             
                        <template x-if="item.type === 'iframe'">
                            <div class="relative w-full max-w-5xl aspect-video max-h-[80vh] shadow-2xl rounded-lg overflow-hidden">                                                                                
                                <iframe 
                                    :src="isOpen && activeIndex === index ? item.src : ''"                              
                                    class="relative z-10 w-full h-full transition-opacity duration-300"                                     
                                    frameborder="0" 
                                    allow="autoplay; fullscreen; picture-in-picture" 
                                    >
                                </iframe>
                            </div>
                        </template>
                        
                        {{-- 3. HTML5 Video (MP4) + Скелетон --}}
                        <template x-if="item.type === 'html5'">
                            <div class="relative max-w-full max-h-[80vh] rounded-lg overflow-hidden">
                                <video 
                                    :src="isOpen && activeIndex === index ? item.src : ''"
                                    :poster="item.poster"                                    
                                    controls 
                                    autoplay
                                    class="relative z-10 max-w-full max-h-[80vh] rounded-lg shadow-2xl transition-opacity duration-300"                                  
                                ></video>
                            </div>
                        </template>

                         {{-- Заголовок --}}                        
                        <template x-if="item.title">
                            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 z-20 max-w-[80%] px-4 py-1.5 bg-black/50 backdrop-blur text-white text-sm rounded-full text-center pointer-events-none" x-text="item.title"></div>                        
                        </template>
                    </div>
                </template>
            </div>

            {{-- Кастомные кнопки навигации --}}
            <div x-show="items.length > 1" class="lightbox-prev absolute left-0 md:left-4 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-background/80 backdrop-blur border border-border rounded-full flex items-center justify-center cursor-pointer text-muted-foreground hover:bg-background hover:text-primary transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-black/95">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </div>
            <div x-show="items.length > 1" class="lightbox-next absolute right-0 md:right-4 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-background/80 backdrop-blur border border-border rounded-full flex items-center justify-center cursor-pointer text-muted-foreground hover:bg-background hover:text-primary transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-black/95">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </div>
       
    </div>
</template>