// resources/js/components/lightbox.js (или components/ui/Lightbox/script.js)

import Swiper from 'swiper';
import { Navigation, Keyboard } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';

export function registerLightbox() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine не загружен, компонент Lightbox не зарегистрирован');
    return;
  }

  window.Alpine.data('lightbox', (config = {}) => ({
    isOpen: false,
    activeIndex: 0,    
    items: config.items || [],
    swiper: null,

     // Состояния кастомного зума
    zoom: 1,
    panX: 0,
    panY: 0,
    isDragging: false,
    dragStartX: 0,
    dragStartY: 0,
    zoomEnabled: config.zoom ?? true, 

    init() {
      this.$watch('isOpen', (val) => {
        document.body.style.overflow = val ? 'hidden' : '';
        if (val) {
          this.$nextTick(() => this.initSwiper());
        } else {
          this.destroySwiper();
        }
      });
    },

    open(index = 0) {
      this.activeIndex = index;
      this.isOpen = true;
    },

    close() {
      this.isOpen = false;
        this.resetZoom(); 
    },

    initSwiper() {
      const el = document.querySelector('.lightbox-swiper');
      if (!el) return;

      const isGlobal = typeof window.Swiper !== 'undefined';
      const SwiperClass = isGlobal ? window.Swiper : Swiper;
      
      const config = {
        initialSlide: this.activeIndex,
        slidesPerView: 1,
        spaceBetween: 10,
        centeredSlides: true,
        navigation: {
          nextEl: '.lightbox-next',
          prevEl: '.lightbox-prev',
        },
        keyboard: true,
        on: {
          slideChange: (sw) => {
            this.activeIndex = sw.activeIndex;
            this.resetZoom(); // Сбрасываем зум при перелистывании
          }
        }
      };

      if (!isGlobal) {
        config.modules = [Navigation, Keyboard];
      }

      this.swiper = new SwiperClass(el, config);
    },

    destroySwiper() {
      if (this.swiper) {
        this.swiper.destroy(true, true);
        this.swiper = null;
      }
    },

    // Логика кастомного зума
    toggleZoom() {
       if (!this.zoomEnabled) return;

      if (this.zoom > 1) {
        this.resetZoom();
      } else {
        this.zoom = 2; // Увеличиваем в 2 раза
        // МАГИЯ: Отключаем свайпы Swiper, чтобы можно было таскать картинку!
        if (this.swiper) this.swiper.allowTouchMove = false;
      }
    },

    resetZoom() {
      this.zoom = 1;
      this.panX = 0;
      this.panY = 0;
      this.isDragging = false;
      // Включаем свайпы обратно
      if (this.swiper) this.swiper.allowTouchMove = true;
    },

    startDrag(e) {
      if (this.zoom === 1) return;
      // Останавливаем стандартное поведение (чтобы картинка не выделялась)
      e.preventDefault();
      e.stopPropagation();
      
      this.isDragging = true;
      this.dragStartX = e.clientX - this.panX;
      this.dragStartY = e.clientY - this.panY;
    },

    doDrag(e) {
      if (!this.isDragging) return;
      this.panX = e.clientX - this.dragStartX;
      this.panY = e.clientY - this.dragStartY;
    },

    stopDrag() {
      this.isDragging = false;
    }
  }));

  console.log('✅ Lightbox компонент зарегистрирован');
}

if (typeof window.Alpine !== 'undefined') {
  registerLightbox();
} else {
  document.addEventListener('alpine:init', () => registerLightbox());
}