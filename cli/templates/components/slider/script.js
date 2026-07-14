import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade, EffectCoverflow, EffectCube, EffectFlip } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

/**
 * Предустановленные рендереры кастомных буллетов
 * ВАЖНО: Используем ! для Tailwind (это !important)
 */
/**
 * Предустановленные рендереры кастомных буллетов
 * ВАЖНО: Используем ! для Tailwind (это !important)
 * Design System: Привязка к семантическим переменным
 */
export const customBulletRenderers = {
    /**
     * Буллеты с номерами (1, 2, 3...)
     */
    numbered: (index, className) => {
        return `<span class="${className} !flex !items-center !justify-center !w-8 !h-8 !rounded-full !bg-accent !text-accent-foreground !text-sm !font-medium !cursor-pointer !transition-colors">${index + 1}</span>`;
    },

    /**
     * Буллеты с номерами и hover эффектом
     */
    numberedActive: (index, className) => {
        return `<span class="${className} !flex !items-center !justify-center !w-8 !h-8 !rounded-full !bg-accent !text-accent-foreground !text-sm !font-medium !cursor-pointer !transition-all hover:!bg-accent/80">${index + 1}</span>`;
    },

    /**
     * Буллеты в виде линий
     */
    lines: (index, className) => {
        return `<span class="${className} !w-8 !h-1 !bg-muted-foreground/40 !rounded !cursor-pointer !transition-all"></span>`;
    },

    /**
     * Буллеты в виде точек с анимацией
     */
    dots: (index, className) => {
        return `<span class="${className} !w-2 !h-2 !rounded-full !bg-muted-foreground !cursor-pointer !transition-all hover:!scale-125"></span>`;
    },

    /**
     * Буллеты в виде квадратов
     */
    squares: (index, className) => {
        return `<span class="${className} !w-3 !h-3 !bg-muted-foreground/40 !cursor-pointer !transition-all !rounded-none"></span>`;
    },

    /**
     * Буллеты в виде ромбов
     */
    diamonds: (index, className) => {
        return `<span class="${className} !w-3 !h-3 !bg-muted-foreground/40 !rotate-45 !cursor-pointer !transition-all !rounded-none"></span>`;
    },
};

export function initSliders() {
    const sliders = document.querySelectorAll('.wp-swiper:not(.swiper-initialized)');
    if (sliders.length === 0) return;
    
    sliders.forEach(el => {
        let config = {};
        try {
            config = JSON.parse(el.dataset.swiperConfig || '{}');
        } catch (e) {
            console.error('Error reading Swiper config:', e);
            return;
        }
        
        const sliderId = el.id;
        const wantNavigation = config.navigation !== false;
        const wantPagination = config.pagination !== false;
        
        // =====================================================================
        // ПОИСК КОНТРОЛОВ
        // =====================================================================
        
        let nextBtn = null;
        let prevBtn = null;
        let pagEl = null;
        
        if (sliderId) {
            nextBtn = document.querySelector(`[data-swiper-next="${sliderId}"]`);
            prevBtn = document.querySelector(`[data-swiper-prev="${sliderId}"]`);
            pagEl = document.querySelector(`[data-swiper-pagination="${sliderId}"]`);
        }
        
        if (!nextBtn) nextBtn = el.querySelector('.wp-swiper-next');
        if (!prevBtn) prevBtn = el.querySelector('.wp-swiper-prev');
        if (!pagEl) pagEl = el.querySelector('.swiper-pagination');
        
        // =====================================================================
        // НАСТРОЙКА НАВИГАЦИИ
        // =====================================================================
        
        if (wantNavigation && nextBtn && prevBtn) {
            config.navigation = {
                nextEl: nextBtn,
                prevEl: prevBtn,
                disabledClass: 'opacity-30 cursor-not-allowed pointer-events-none',
                hiddenClass: 'hidden',
                lockClass: 'swiper-navigation-lock',
            };
        } else {
            delete config.navigation;
        }
        
        // =====================================================================
        // НАСТРОЙКА ПАГИНАЦИИ
        // =====================================================================
        
        if (wantPagination && pagEl) {
            // Защита: если pagination = true, превращаем в пустой объект
            const originalPagination = (typeof config.pagination === 'object' && config.pagination !== null) ? config.pagination : {};
            
            config.pagination = {
                el: pagEl,
                clickable: true,
                type: originalPagination.type || 'bullets',
            };
            
            if (originalPagination.customType && customBulletRenderers[originalPagination.customType]) {
                config.pagination.renderBullet = customBulletRenderers[originalPagination.customType];
            }
        } else {
            delete config.pagination;
        }
        
        // =====================================================================
        // НАСТРОЙКА МОДУЛЕЙ
        // =====================================================================
        
        const modules = [Navigation, Pagination, Autoplay];
        
        if (config.effect === 'fade') modules.push(EffectFade);
        if (config.effect === 'coverflow') modules.push(EffectCoverflow);
        if (config.effect === 'cube') modules.push(EffectCube);
        if (config.effect === 'flip') modules.push(EffectFlip);
        
        // =====================================================================
        // ИНИЦИАЛИЗАЦИЯ SWIPER
        // =====================================================================
        
        try {
            const swiperInstance = new Swiper(el, {
                ...config,
                modules: modules,
            });
            
            el._swiper = swiperInstance;
            
        } catch (e) {
            console.error('Error initializing Swiper:', e);
        }
    });
}

// =====================================================================
// ИНИЦИАЛИЗАЦИЯ
// =====================================================================

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSliders);
} else {
    initSliders();
}

document.addEventListener('alpine:navigated', initSliders);
document.addEventListener('livewire:load', initSliders);
document.addEventListener('livewire:update', initSliders);
document.addEventListener('turbo:load', initSliders);
document.addEventListener('htmx:afterSwap', initSliders);

window.initSliders = initSliders;
window.customBulletRenderers = customBulletRenderers;