/**
 * Компонент Slider на базе Swiper.js
 * Версия: 2.0.0 (продакшен)
 * 
 * Поддерживает:
 * - default – контролы внутри слайдера (overlay)
 * - external – внешние элементы через data-атрибуты
 * 
 * Внешние контролы:
 *   <button data-swiper-prev="slider-id">←</button>
 *   <button data-swiper-next="slider-id">→</button>
 *   <div data-swiper-pagination="slider-id"></div>
 */
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade, EffectCoverflow, EffectCube, EffectFlip } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
// Импортируйте дополнительные стили при необходимости:
// import 'swiper/css/effect-fade';
// import 'swiper/css/effect-coverflow';
// import 'swiper/css/effect-cube';
// import 'swiper/css/effect-flip';

export function initSliders() {
    const sliders = document.querySelectorAll('.wp-swiper:not(.swiper-initialized)');
    if (sliders.length === 0) return;
    
    sliders.forEach(el => {
        // Парсим конфиг
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
        // ПОИСК КОНТРОЛОВ (приоритет: external > default)
        // =====================================================================
        
        let nextBtn = null;
        let prevBtn = null;
        let pagEl = null;
        
        // 1. Внешние контролы (через data-атрибуты)
        if (sliderId) {
            nextBtn = document.querySelector(`[data-swiper-next="${sliderId}"]`);
            prevBtn = document.querySelector(`[data-swiper-prev="${sliderId}"]`);
            pagEl = document.querySelector(`[data-swiper-pagination="${sliderId}"]`);
        }
        
        // 2. Если внешних нет, ищем внутри слайдера
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
            config.pagination = {
                el: pagEl,
                clickable: true,
                dynamicBullets: false,
                dynamicMainBullets: 1,
                type: 'bullets', // 'bullets' | 'fraction' | 'progressbar' | 'custom'
                bulletClass: 'swiper-pagination-bullet',
                bulletActiveClass: 'swiper-pagination-bullet-active',
                modifierClass: 'swiper-pagination-',
                currentClass: 'swiper-pagination-current',
                totalClass: 'swiper-pagination-total',
                hiddenClass: 'swiper-pagination-hidden',
                progressbarOpposite: false,
                clickableClass: 'swiper-pagination-clickable',
                lockClass: 'swiper-pagination-lock',
            };
        } else {
            delete config.pagination;
        }
        
        // =====================================================================
        // НАСТРОЙКА МОДУЛЕЙ
        // =====================================================================
        
        // Определяем какие модули нужны
        const modules = [Navigation, Pagination, Autoplay];
        
        // Добавляем модули эффектов, если они используются
        if (config.effect === 'fade') {
            modules.push(EffectFade);
        }
        if (config.effect === 'coverflow') {
            modules.push(EffectCoverflow);
        }
        if (config.effect === 'cube') {
            modules.push(EffectCube);
        }
        if (config.effect === 'flip') {
            modules.push(EffectFlip);
        }
        
        // =====================================================================
        // ИНИЦИАЛИЗАЦИЯ SWIPER
        // =====================================================================
        
        try {
            const swiperInstance = new Swiper(el, {
                ...config,
                modules: modules,
                // Фолбэки для стабильности
                watchSlidesProgress: true,
                watchSlidesVisibility: true,
                // Отключаем лишние вычисления для производительности
                passiveListeners: true,
                // Логирование ошибок
                on: {
                    init: function() {
                        // Можно добавить кастомную логику при инициализации
                        // console.log('Swiper инициализирован:', el.id);
                    },
                    error: function(e) {
                        console.error('Swiper ошибка:', e);
                    }
                }
            });
            
            // Сохраняем инстанс в DOM для отладки
            el._swiper = swiperInstance;
            
        } catch (e) {
            console.error('Ошибка инициализации Swiper:', e);
        }
    });
}

// =====================================================================
// ИНИЦИАЛИЗАЦИЯ
// =====================================================================

// При загрузке страницы
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSliders);
} else {
    initSliders();
}

// Re-init при навигации в Alpine.js
document.addEventListener('alpine:navigated', initSliders);

// Re-init при Livewire обновлениях
document.addEventListener('livewire:load', initSliders);
document.addEventListener('livewire:update', initSliders);

// Re-init при Turbo (Hotwire)
document.addEventListener('turbo:load', initSliders);

// Re-init при htmx
document.addEventListener('htmx:afterSwap', initSliders);

// Экспортируем для ручного вызова
window.initSliders = initSliders;