// resources/views/components/product-gallery/script.js

import Swiper from 'swiper';
import { Navigation, Thumbs, Keyboard, Mousewheel } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';

// Хелпер для глубокого слияния объектов настроек
function deepMerge(target, ...sources) {
  if (!sources.length) return target;
  const source = sources.shift();
  if (isObject(target) && isObject(source)) {
    for (const key in source) {
      if (isObject(source[key])) {
        if (!target[key]) Object.assign(target, { [key]: {} });
        deepMerge(target[key], source[key]);
      } else {
        Object.assign(target, { [key]: source[key] });
      }
    }
  }
  return target;
}
function isObject(item) {
  return item && typeof item === 'object' && !Array.isArray(item);
}

/*================================================================================================================
регестрируем горизонтальную галлерею
==================================================================================================================*/
export function registerProductGalleryHorizontal() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine is not loaded, ProductGalleryHorizontal component not registered');
    return;
  }

  window.Alpine.data('productGalleryHorizontal', (customConfig = {}) => ({
    mainSwiper: null,
    thumbsSwiper: null,
    mouseX: 0,
    mouseY: 0,
    _slideToHandler: null,

    init() {
      requestAnimationFrame(() => {
        let initialSlide = 0;
        const wrapper = this.$el.closest('[data-current-slide]');
        if (wrapper) {
          initialSlide = parseInt(wrapper.getAttribute('data-current-slide')) || 0;
        }

        // МАГИЯ FIX: Защита $refs, если тумбнейлов нет (всего 1 картинка)
        if (this.$refs.thumbsRef) {
          const defaultThumbsConfig = {
            spaceBetween: 5,
            slidesPerView: 4,
            initialSlide: initialSlide,
            watchSlidesProgress: true,
            breakpoints: {
              640: { slidesPerView: 5, spaceBetween: 5 },
              1024: { slidesPerView: 6, spaceBetween: 5 },
            },
            mousewheel: true,
          };
          const thumbsConfig = deepMerge(defaultThumbsConfig, customConfig.thumbs || {});
          thumbsConfig.modules = [Navigation, Mousewheel];
          thumbsConfig.navigation = { nextEl: this.$refs.thumbNext, prevEl: this.$refs.thumbPrev };

          this.thumbsSwiper = new Swiper(this.$refs.thumbsRef, thumbsConfig);
        }

        if (this.$refs.mainRef) {
          const defaultMainConfig = {
            spaceBetween: 5,
            initialSlide: initialSlide,
            keyboard: { enabled: true, onlyInViewport: true },
            thumbs: this.thumbsSwiper ? { swiper: this.thumbsSwiper } : {},
            on: {
              slideChange: (sw) => {
                this.activeIndex = sw.activeIndex;
                this.resetZoom();
                this.$dispatch('gallery-sync-slide', { index: sw.activeIndex });
              }
            }
          };
          const mainConfig = deepMerge(defaultMainConfig, customConfig.main || {});
          mainConfig.modules = [Navigation, Thumbs, Keyboard];
          mainConfig.navigation = { nextEl: this.$refs.mainNext, prevEl: this.$refs.mainPrev };

          this.mainSwiper = new Swiper(this.$refs.mainRef, mainConfig);
        }

        if (this.$refs.thumbsWrapper) this.$refs.thumbsWrapper.style.cssText = '';
        if (this.$refs.mainRef) this.$refs.mainRef.style.cssText = '';

        this._slideToHandler = (e) => {
          if (this.mainSwiper && typeof e.detail.index !== 'undefined') {
            this.mainSwiper.slideTo(e.detail.index);
          }
        };
        window.addEventListener('gallery-slide-to', this._slideToHandler);
      });
    },

    destroy() {
      if (this._slideToHandler) window.removeEventListener('gallery-slide-to', this._slideToHandler);
      if (this.mainSwiper) this.mainSwiper.destroy(true, true);
      if (this.thumbsSwiper) this.thumbsSwiper.destroy(true, true);
    },

    moveParallax(e) {
      const rect = e.currentTarget.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
      const y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
      this.mouseX = x * 10;
      this.mouseY = y * 10;
    },

    resetParallax() {
      this.mouseX = 0;
      this.mouseY = 0;
    },
  }));

  console.log('✅ ProductGalleryHorizontal component registered');
}

/*================================================================================================================
регестрируем вертикальную галлерею
==================================================================================================================*/
export function registerProductGalleryVertical() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine is not loaded, ProductGalleryVertical component not registered');
    return;
  }

  window.Alpine.data('productGalleryVertical', (customConfig = {}) => ({
    mainSwiper: null,
    thumbsSwiper: null,
    mouseX: 0,
    mouseY: 0,
    _slideToHandler: null,

    init() {
      requestAnimationFrame(() => {
        let initialSlide = 0;
        const wrapper = this.$el.closest('[data-current-slide]');
        if (wrapper) {
          initialSlide = parseInt(wrapper.getAttribute('data-current-slide')) || 0;
        }

        if (this.$refs.thumbsRef) {
          const defaultThumbsConfig = {
            direction: 'vertical',
            spaceBetween: 5,
            slidesPerView: 4,
            initialSlide: initialSlide, 
            watchSlidesProgress: true,
            breakpoints: {
              640: { slidesPerView: 5, spaceBetween: 5 },
              1024: { slidesPerView: 6, spaceBetween: 5 },
            },
            mousewheel: true,
          };
          const thumbsConfig = deepMerge(defaultThumbsConfig, customConfig.thumbs || {});
          thumbsConfig.modules = [Navigation, Mousewheel];
          thumbsConfig.navigation = { nextEl: this.$refs.thumbNext, prevEl: this.$refs.thumbPrev };

          this.thumbsSwiper = new Swiper(this.$refs.thumbsRef, thumbsConfig);
        }

        if (this.$refs.mainRef) {
          const defaultMainConfig = {
            spaceBetween: 5,
            initialSlide: initialSlide,
            keyboard: { enabled: true, onlyInViewport: true },
            thumbs: this.thumbsSwiper ? { swiper: this.thumbsSwiper } : {},
            on: {
              slideChange: (sw) => {
                this.activeIndex = sw.activeIndex;
                this.resetZoom();
                this.$dispatch('gallery-sync-slide', { index: sw.activeIndex });
              }
            }
          };
          const mainConfig = deepMerge(defaultMainConfig, customConfig.main || {});
          mainConfig.modules = [Navigation, Thumbs, Keyboard];
          mainConfig.navigation = { nextEl: this.$refs.mainNext, prevEl: this.$refs.mainPrev };

          this.mainSwiper = new Swiper(this.$refs.mainRef, mainConfig);
        }

        if (this.$refs.thumbsWrapper) this.$refs.thumbsWrapper.style.opacity = '1';
        if (this.$refs.mainRef) this.$refs.mainRef.style.opacity = '1';

        this._slideToHandler = (e) => {
          if (this.mainSwiper && typeof e.detail.index !== 'undefined') {
            this.mainSwiper.slideTo(e.detail.index);
          }
        };
        window.addEventListener('gallery-slide-to', this._slideToHandler);
      });
    },

    destroy() {
      if (this._slideToHandler) window.removeEventListener('gallery-slide-to', this._slideToHandler);
      if (this.mainSwiper) this.mainSwiper.destroy(true, true);
      if (this.thumbsSwiper) this.thumbsSwiper.destroy(true, true);
    },

    moveParallax(e) {
      const rect = e.currentTarget.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
      const y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
      this.mouseX = x * 10;
      this.mouseY = y * 10;
    },

    resetParallax() {
      this.mouseX = 0;
      this.mouseY = 0;
    },
  }));

  console.log('✅ ProductGalleryVertical component registered');
}

// МАГИЯ FIX: Жестко вешаем слушатель на alpine:init
document.addEventListener('alpine:init', () => {
  registerProductGalleryHorizontal();
  registerProductGalleryVertical();
});