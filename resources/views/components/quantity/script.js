/**
 * Компонент Quantity для Alpine.js
 * 
 * Шагатор количества товара. 
 * Отправляет событие 'quantity-updated' при изменении значения.
 */

class QuantityComponent {
  constructor(props) {
    this.qty = props.qty ?? 1;
    this.min = props.min ?? 1;
    this.max = props.max ?? 9999;
    this.step = props.step ?? 1;
  }

  init() {
    // При инициализации приводим значение в порядок (на случай кривого дефолта)
    this.update();
  }

  update() {
    let val = parseFloat(this.qty);
    if (isNaN(val)) val = this.min;
    if (val < this.min) val = this.min;
    if (val > this.max) val = this.max;
    this.qty = val;
    this.$dispatch('quantity-updated', val);
  }

  increment() {
    let val = parseFloat(this.qty) + parseFloat(this.step);
    if (val > this.max) val = this.max;
    this.qty = val;
    this.$dispatch('quantity-updated', val);
  }

  decrement() {
    let val = parseFloat(this.qty) - parseFloat(this.step);
    if (val < this.min) val = this.min;
    this.qty = val;
    this.$dispatch('quantity-updated', val);
  }
}

export function registerQuantity() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine is not loaded, Quantity component not registered');
    return;
  }
  window.Alpine.data('quantity', (props) => new QuantityComponent(props));
  console.log('✅ Quantity component registered');
}

// Жестко вешаем слушатель на alpine:init
document.addEventListener('alpine:init', () => {
  registerQuantity();
});

export default QuantityComponent;