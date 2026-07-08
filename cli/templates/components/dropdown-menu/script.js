// dropdown-menu/script.js

/**
 * Компонент dropdown-меню для Alpine.js.
 * Поддерживает многоуровневую вложенность, автопозиционирование fixed-меню,
 * блокировку скролла body и плавный скролл к открытому пункту.
 */
class DropdownMenu {
  constructor(config) {
    this.level = config.level || 0;
    this.delay = config.delay || 500;
    this.hasChildren = config.hasChildren || false;

    // Состояние
    this.hover = false;
    this.isOpen = false;
    this.closeTimeout = null;
    this.openRight = true;
    this.fixedPos = { left: 0, top: 0, maxHeight: 0 };

    // Константы в rem (для независимости от zoom/шрифтов)
    this.GAP = 0.125; // отступ между триггером и меню
    this.EDGE_PADDING = 0.625; // отступ от краёв viewport
    this.SCROLL_OFFSET = 3.125; // отступ при скролле к пункту
  }

  /** px → rem (относительно root font-size) */
  pxToRem(px) {
    const fontSize = parseFloat(
      getComputedStyle(document.documentElement).fontSize,
    );
    return px / fontSize;
  }

  /** rem → px */
  remToPx(rem) {
    const fontSize = parseFloat(
      getComputedStyle(document.documentElement).fontSize,
    );
    return rem * fontSize;
  }

  /**
   * Измерение ширины меню (даже если оно скрыто через display: none).
   * Временно показывает меню через visibility: hidden, измеряет, возвращает обратно.
   */
  measureMenuWidth() {
    const menuEl = this.$el.querySelector(':scope > div:last-child');
    if (!menuEl) return this.remToPx(11.25); // fallback: w-45 = 11.25rem

    // Сохраняем текущие стили
    const originalDisplay = menuEl.style.display;
    const originalVisibility = menuEl.style.visibility;
    const originalPosition = menuEl.style.position;

    // Временно показываем (невидимо)
    menuEl.style.display = 'block';
    menuEl.style.visibility = 'hidden';
    menuEl.style.position = 'fixed';

    // Измеряем
    const width = menuEl.offsetWidth;

    // Возвращаем стили
    menuEl.style.display = originalDisplay;
    menuEl.style.visibility = originalVisibility;
    menuEl.style.position = originalPosition;

    return width;
  }

  /** Расчёт позиции fixed-меню относительно триггера (.dropdown-li-full) */
  calcPosition() {
    if (this.level === 0) return;

    const trigger = this.$el.querySelector(':scope > .dropdown-li-full');
    if (!trigger) return;

    const rect = trigger.getBoundingClientRect();
    const triggerLeft = this.pxToRem(rect.left);
    const triggerRight = this.pxToRem(rect.right);
    const triggerTop = this.pxToRem(rect.top);
    const viewportWidth = this.pxToRem(window.innerWidth);
    const viewportHeight = this.pxToRem(window.innerHeight);

    // ИЗМЕНЕНО: измеряем реальную ширину меню (даже если скрыто)
    const menuWidthPx = this.measureMenuWidth();
    const menuWidth = this.pxToRem(menuWidthPx);

    // Определяем сторону открытия (вправо/влево)
    const spaceRight = viewportWidth - triggerRight;
    this.openRight = spaceRight > menuWidth + this.EDGE_PADDING;

    let left = this.openRight
      ? triggerRight + this.GAP
      : triggerLeft - menuWidth - this.GAP;
    let top = triggerTop;

    // Ограничиваем позицию в пределах viewport
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
    if (this.hover && this.level > 0) {
      this.calcPosition();
    }
  }

  /** Блокировка скролла body (с подсчётом вложенных меню) */
  lockScroll() {
    if (typeof window.__dropdownLockCount === 'undefined') {
      window.__dropdownLockCount = 0;
    }
    window.__dropdownLockCount++;
    if (window.__dropdownLockCount === 1) {
      document.body.style.overflow = 'hidden';
      document.documentElement.style.overflow = 'hidden';
    }
  }

  /** Разблокировка скролла body */
  unlockScroll() {
    if (
      typeof window.__dropdownLockCount === 'undefined' ||
      window.__dropdownLockCount === 0
    ) {
      window.__dropdownLockCount = 0;
      return;
    }
    window.__dropdownLockCount--;
    if (window.__dropdownLockCount === 0) {
      document.body.style.overflow = '';
      document.documentElement.style.overflow = '';
    }
  }

  /** Обработчик mouseenter: открытие + блокировка скролла для top-level */
  onMouseEnter() {
    clearTimeout(this.closeTimeout);
    this.hover = true;
    this.calcPosition();
    if (this.hasChildren && this.level <= 1) {
      this.lockScroll();
    }
  }

  /** Отложенное закрытие (hover-intent) */
  scheduleClose() {
    clearTimeout(this.closeTimeout);
    this.closeTimeout = setTimeout(() => {
      this.hover = false;
      if (this.hasChildren && this.level <= 1) {
        this.unlockScroll();
      }
    }, this.delay);
  }

  /** Клик по пункту level > 1: toggle + авто-скролл к пункту */
  toggleOpen(e) {
    this.isOpen = !this.isOpen;
    if (this.isOpen) {
      this.$nextTick(() => {
        const container = this.$el.closest('.overflow-y-auto');
        if (!container) return;

        const containerRect = container.getBoundingClientRect();
        const elRect = this.$el.getBoundingClientRect();

        const containerTopRem = this.pxToRem(containerRect.top);
        const elTopRem = this.pxToRem(elRect.top);
        const scrollTopRem =
          this.pxToRem(container.scrollTop) +
          (elTopRem - containerTopRem) -
          this.SCROLL_OFFSET;

        container.scrollTo({
          top: this.remToPx(scrollTopRem),
          behavior: 'smooth',
        });
      });
    }
  }

  /** Alpine lifecycle: подписка на scroll/resize для пересчёта позиции */
  init() {
    if (this.level > 0) {
      window.addEventListener('scroll', this.updatePosition.bind(this), {
        passive: true,
      });
      window.addEventListener('resize', this.updatePosition.bind(this), {
        passive: true,
      });
    }
    if (typeof window.__dropdownLockCount === 'undefined') {
      window.__dropdownLockCount = 0;
    }
  }
}

/** Регистрация компонента dropdown в Alpine */
export function registerDropdown() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine не загружен');
    return;
  }

  window.Alpine.data('dropdown', (config) => new DropdownMenu(config));
  console.log('✅ Dropdown компонент зарегистрирован');
}

// Авто-регистрация: сразу или после alpine:init
if (typeof window.Alpine !== 'undefined') {
  registerDropdown();
} else {
  document.addEventListener('alpine:init', () => registerDropdown());
}

export default DropdownMenu;
