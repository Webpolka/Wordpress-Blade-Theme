// dropdown-menu/script.js

/**
 * Компонент dropdown-меню для Alpine.js (Refactored)
 * Без блокировки body, с правильной очисткой слушателей и кэшированием rem.
 */
class DropdownMenu {
  constructor(config) {
    this.level = config.level || 0;
    this.delay = config.delay || 300;
    this.hasChildren = config.hasChildren || false;

    // Состояние
    this.hover = false;
    this.isOpen = false;
    this.closeTimeout = null;
    this.fixedPos = { left: 0, top: 0, maxHeight: 0 };

    // Константы в rem
    this.GAP = 0.125;
    this.EDGE_PADDING = 0.625;

    // Кэшируем размер шрифта для оптимизации rem-математики
    this.rootFontSize = parseFloat(getComputedStyle(document.documentElement).fontSize);

    // Биндим методы для возможности их снять позже
    this._updatePosition = this.updatePosition.bind(this);
  }

  pxToRem(px) {
    return px / this.rootFontSize;
  }

  remToPx(rem) {
    return rem * this.rootFontSize;
  }

  /**
   * Безопасное измерение ширины скрытого меню
   */
  measureMenuWidth() {
    const menuEl = this.$el.querySelector(':scope > div:last-child');
    if (!menuEl) return 180; // fallback ~ 11.25rem

    // Alpine использует x-show (display: none). 
    // Временно убираем его, не трогая position, чтобы измерить.
    const originalDisplay = menuEl.style.display;
    menuEl.style.visibility = 'hidden';
    menuEl.style.display = 'block';

    const width = menuEl.offsetWidth;

    // Возвращаем как было
    menuEl.style.display = originalDisplay;
    menuEl.style.visibility = '';

    return width;
  }

  /** Расчёт позиции fixed-меню относительно триггера */
  calcPosition() {
    if (this.level !== 1) return; // Позиционируем только Level 1

    const trigger = this.$el.querySelector(':scope > .dropdown-li-full');
    if (!trigger) return;

    const rect = trigger.getBoundingClientRect();
    const triggerLeft = this.pxToRem(rect.left);
    const triggerRight = this.pxToRem(rect.right);
    const triggerTop = this.pxToRem(rect.top);
    const viewportWidth = this.pxToRem(window.innerWidth);
    const viewportHeight = this.pxToRem(window.innerHeight);

    const menuWidth = this.pxToRem(this.measureMenuWidth());

    const spaceRight = viewportWidth - triggerRight;
    const openRight = spaceRight > menuWidth + this.EDGE_PADDING;

    let left = openRight
      ? triggerRight + this.GAP
      : triggerLeft - menuWidth - this.GAP;
    let top = triggerTop;

    if (left + menuWidth > viewportWidth - this.EDGE_PADDING) {
      left = viewportWidth - menuWidth - this.EDGE_PADDING;
    }
    if (left < this.EDGE_PADDING) {
      left = this.EDGE_PADDING;
    }
    if (top < this.EDGE_PADDING) {
      top = this.EDGE_PADDING;
    }

    const maxHeight = viewportHeight - top - this.EDGE_PADDING;
    this.fixedPos = { left, top, maxHeight };
  }

  /** Обновление позиции при скролле/ресайзе */
  updatePosition() {
    if (this.hover && this.level === 1) {
      this.calcPosition();
    }
  }

  /** Обработчик mouseenter */
  onMouseEnter() {
    clearTimeout(this.closeTimeout);
    this.hover = true;
    
    if (this.hasChildren && this.level <= 1) {
      // Считаем позицию сразу при наведении
      this.calcPosition();
    }
  }

  /** Отложенное закрытие (hover-intent) */
  scheduleClose() {
    clearTimeout(this.closeTimeout);
    this.closeTimeout = setTimeout(() => {
      this.hover = false;
    }, this.delay);
  }

  /** Клик по пункту level > 1: toggle + авто-скролл */
  toggleOpen(e) {
    this.isOpen = !this.isOpen;
    if (this.isOpen) {
      this.$nextTick(() => {
        const container = this.$el.closest('.overflow-y-auto');
        if (!container) return;

        const containerRect = container.getBoundingClientRect();
        const elRect = this.$el.getBoundingClientRect();

        // Простая и надежная математика скролла
        const scrollOffset = elRect.top - containerRect.top + container.scrollTop - 50; // 50px отступ сверху

        container.scrollTo({
          top: scrollOffset,
          behavior: 'smooth',
        });
      });
    }
  }

  /**
   * Alpine lifecycle: вызывается при инициализации
   */
  init() {
    if (this.level > 0) {
      window.addEventListener('scroll', this._updatePosition, { passive: true });
      window.addEventListener('resize', this._updatePosition, { passive: true });
    }
  }

  /**
   * ВАЖНО: Alpine lifecycle: вызывается при удалении элемента из DOM
   * Чистим память и таймеры, чтобы не было багов и зависаний
   */
  destroy() {
    clearTimeout(this.closeTimeout);
    if (this.level > 0) {
      window.removeEventListener('scroll', this._updatePosition);
      window.removeEventListener('resize', this._updatePosition);
    }
  }
}

export function registerDropdown() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine не загружен, компонент Dropdown не зарегистрирован');
    return;
  }

  window.Alpine.data('dropdown', (config) => new DropdownMenu(config));
  console.log('✅ Dropdown компонент зарегистрирован');
}

if (typeof window.Alpine !== 'undefined') {
  registerDropdown();
} else {
  document.addEventListener('alpine:init', () => registerDropdown());
}

export default DropdownMenu;
