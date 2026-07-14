/**
 * Компонент Stat для Alpine.js
 * 
 * Анимированный счетчик от 0 до заданного числа.
 * Запускается только когда элемент попадает в зону видимости (Viewport).
 */

class StatComponent {
  constructor(props) {
    this.target = props.target ?? 0;
    this.duration = props.duration ?? 2000;
    
    this.current = 0;
    this.started = false;
  }

  init() {
    // МАГИЯ IntersectionObserver: запускаем анимацию только когда элемент видим
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !this.started) {
          this.started = true;
          this.animate();
          observer.unobserve(this.$el); // Останавливаем наблюдение после старта
        }
      });
    }, { threshold: 0.3 }); // Порог: 30% элемента должно быть видно

    observer.observe(this.$el);
  }

  animate() {
    let startTime = null;
    
    const step = (timestamp) => {
      if (!startTime) startTime = timestamp;
      const progress = Math.min((timestamp - startTime) / this.duration, 1);
      
      // Плавное замедление (Ease-out cubic)
      const eased = 1 - Math.pow(1 - progress, 3);
      this.current = Math.floor(eased * this.target);
      
      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        this.current = this.target; // Гарантируем точное финальное число
      }
    };
    
    requestAnimationFrame(step);
  }
}

export function registerStat() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine is not loaded, Stat component not registered');
    return;
  }
  
  window.Alpine.data('stat', (props = {}) => new StatComponent(props));
  console.log('✅ Stat component registered');
}

// МАГИЯ FIX: Жестко вешаем слушатель на alpine:init
document.addEventListener('alpine:init', () => {
  registerStat();
});

export default StatComponent;