/**
 * Компонент Slider на базе Swiper.js
 * Версия: 2.2.0 (продакшен)
 * 
 * Поддерживает:
 * - default – контролы внутри слайдера (overlay/top/bottom)
 * - external – внешние элементы через data-атрибуты
 * - Кастомные буллеты через customType
 * 
 * Внешние контролы:
 *   <button data-swiper-prev="slider-id">←</button>
 *   <button data-swiper-next="slider-id">→</button>
 *   <div data-swiper-pagination="slider-id"></div>
 * 
 * Кастомные буллеты:
 *   'pagination' => ['customType' => 'lines']
 *   Доступные типы: numbered, numberedActive, lines, dots, squares, diamonds
 */

import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade, EffectCoverflow, EffectCube, EffectFlip } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

/**
 * Предустановленные рендереры кастомных буллетов
 * ВАЖНО: Используем ! для Tailwind (это !important)
 */
export const customBulletRenderers = {
    /**
     * Буллеты с номерами (1, 2, 3...)
     */
    numbered: (index, className) => {
        return `<span class="${className} !flex !items-center !justify-center !w-8 !h-8 !rounded-full !bg-gray-200 dark:!bg-gray-700 !text-sm !font-medium !cursor-pointer !transition-colors !text-gray-700 dark:!text-gray-200">${index + 1}</span>`;
    },

    /**
     * Буллеты с номерами и hover эффектом
     */
    numberedActive: (index, className) => {
        return `<span class="${className} !flex !items-center !justify-center !w-8 !h-8 !rounded-full !bg-gray-200 dark:!bg-gray-700 !text-sm !font-medium !cursor-pointer !transition-all hover:!bg-gray-300 dark:hover:!bg-gray-600 !text-gray-700 dark:!text-gray-200">${index + 1}</span>`;
    },

    /**
     * Буллеты в виде линий
     */
    lines: (index, className) => {
        return `<span class="${className} !w-8 !h-1 !bg-gray-300 dark:!bg-gray-600 !rounded !cursor-pointer !transition-all"></span>`;
    },

    /**
     * Буллеты в виде точек с анимацией
     */
    dots: (index, className) => {
        return `<span class="${className} !w-2 !h-2 !rounded-full !bg-gray-400 dark:!bg-gray-500 !cursor-pointer !transition-all hover:!scale-125"></span>`;
    },

    /**
     * Буллеты в виде квадратов
     */
    squares: (index, className) => {
        return `<span class="${className} !w-3 !h-3 !bg-gray-300 dark:!bg-gray-600 !cursor-pointer !transition-all !rounded-none"></span>`;
    },

    /**
     * Буллеты в виде ромбов
     */
    diamonds: (index, className) => {
        return `<span class="${className} !w-3 !h-3 !bg-gray-300 dark:!bg-gray-600 !rotate-45 !cursor-pointer !transition-all !rounded-none"></span>`;
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
            console.error('Ошибка чтения конфига Swiper:', e);
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
            console.error('Ошибка инициализации Swiper:', e);
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