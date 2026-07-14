/**
 * RangeSlider Component for Alpine.js
 * 
 * Поддерживает одиночный режим и диапазон (dual).
 * Отправляет данные через скрытые input.
 */

class RangeSliderComponent {
  constructor(props) {
    this.min = props.min ?? 0;
    this.max = props.max ?? 100;
    this.step = props.step ?? 1;
    this.isDual = props.dual ?? false;
    
    // Начальные значения
    this.minVal = props.minVal ?? this.min;
    this.maxVal = props.maxVal ?? this.max;
    
    this.dragging = null; // 'min' | 'max' | null
  }

  init() {
    // Глобальные слушатели для перетаскивания
    window.addEventListener('mousemove', (e) => this.onMove(e));
    window.addEventListener('mouseup', () => this.stopDrag());
    
    // Для тач-устройств
    window.addEventListener('touchmove', (e) => this.onMove(e), { passive: false });
    window.addEventListener('touchend', () => this.stopDrag());
  }

  get minPercent() {
    return ((this.minVal - this.min) / (this.max - this.min)) * 100;
  }

  get maxPercent() {
    return ((this.maxVal - this.min) / (this.max - this.min)) * 100;
  }

  startDrag(which) {
    this.dragging = which;
  }

  stopDrag() {
    if (this.dragging) {
      this.$dispatch('range-changed', { min: this.minVal, max: this.maxVal });
    }
    this.dragging = null;
  }

  onMove(e) {
    if (!this.dragging) return;
    
    e.preventDefault(); // Предотвращаем скролл страницы на тач-экранах
    
    const track = this.$refs.track;
    if (!track) return;
    
    const rect = track.getBoundingClientRect();
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    
    let percent = (clientX - rect.left) / rect.width;
    percent = Math.max(0, Math.min(1, percent));
    
    let rawValue = this.min + percent * (this.max - this.min);
    // Применяем шаг
    rawValue = Math.round(rawValue / this.step) * this.step;
    rawValue = Math.max(this.min, Math.min(this.max, rawValue));

    if (this.dragging === 'min') {
      this.minVal = Math.min(rawValue, this.maxVal - this.step);
    } else if (this.dragging === 'max') {
      this.maxVal = Math.max(rawValue, this.minVal + this.step);
    } else if (this.dragging === 'single') {
      this.minVal = rawValue;
      this.maxVal = rawValue;
    }
  }

  // Клик по самому треку (перемещаем ближайший бегунок)
  onTrackClick(e) {
    const track = this.$refs.track;
    const rect = track.getBoundingClientRect();
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    
    let percent = (clientX - rect.left) / rect.width;
    let rawValue = this.min + percent * (this.max - this.min);
    rawValue = Math.round(rawValue / this.step) * this.step;

    if (this.isDual) {
      // Определяем, к какому бегунку ближе клик
      const distToMin = Math.abs(rawValue - this.minVal);
      const distToMax = Math.abs(rawValue - this.maxVal);
      
      if (distToMin < distToMax) {
        this.minVal = Math.min(rawValue, this.maxVal - this.step);
        this.startDrag('min');
      } else {
        this.maxVal = Math.max(rawValue, this.minVal + this.step);
        this.startDrag('max');
      }
    } else {
      this.minVal = rawValue;
      this.maxVal = rawValue;
      this.startDrag('single');
    }
  }
}

export function registerRangeSlider() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine is not loaded, RangeSlider component not registered');
    return;
  }
  window.Alpine.data('rangeSlider', (props) => new RangeSliderComponent(props));
  console.log('✅ RangeSlider component registered');
}

document.addEventListener('alpine:init', () => {
  registerRangeSlider();
});

export default RangeSliderComponent;