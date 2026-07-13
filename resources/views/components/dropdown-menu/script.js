// dropdown-menu/script.js

/**
 * Dropdown Menu Component for Alpine.js
 * No hidden element measurement, features CSS-flip, listener cleanup, and rem-math.
 */
class DropdownMenu {
  constructor(config) {
    this.level = config.level || 0;
    this.delay = config.delay || 300;
    this.hasChildren = config.hasChildren || false;

    this.hover = false;
    this.isOpen = false;
    this.closeTimeout = null;
    
    this.fixedPos = { left: 'auto', right: 'auto', top: '-9999rem', maxHeight: '0rem' };

    this._updatePosition = this.updatePosition.bind(this);
  }

  pxToRem(px) {
    const rootFontSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
    return (px / rootFontSize) + 'rem';
  }

  calcPosition() {
    if (this.level !== 1) return;

    const trigger = this.$el.querySelector(':scope > .dropdown-li-full');
    if (!trigger) return;

    const rect = trigger.getBoundingClientRect();
    const viewportWidth = document.documentElement.clientWidth;
    const viewportHeight = window.innerHeight;

    const gap = 3;
    const edgePadding = 10;
    const estimatedMenuWidth = 192;
    
    const spaceRight = viewportWidth - rect.right;
    const openRight = spaceRight > estimatedMenuWidth + edgePadding;

    const topPx = Math.max(edgePadding, rect.top);
    const maxHeightPx = viewportHeight - topPx - edgePadding;

    if (openRight) {
      this.fixedPos = {
        left: this.pxToRem(rect.right + gap),
        right: 'auto',
        top: this.pxToRem(topPx),
        maxHeight: this.pxToRem(maxHeightPx)
      };
    } else {
      this.fixedPos = {
        left: 'auto',
        right: this.pxToRem(viewportWidth - rect.left + gap),
        top: this.pxToRem(topPx),
        maxHeight: this.pxToRem(maxHeightPx)
      };
    }
  }

  updatePosition() {
    if (this.hover && this.level === 1) {
      this.calcPosition();
    }
  }

  onMouseEnter() {
    clearTimeout(this.closeTimeout);
    this.hover = true;
    
    if (this.hasChildren && this.level <= 1) {
      this.calcPosition();
    }
  }

  scheduleClose() {
    clearTimeout(this.closeTimeout);
    this.closeTimeout = setTimeout(() => {
      this.hover = false;
    }, this.delay);
  }

  toggleOpen(e) {
    this.isOpen = !this.isOpen;
    if (this.isOpen) {
      this.$nextTick(() => {
        const container = this.$el.closest('.overflow-y-auto');
        if (!container) return;

        const containerRect = container.getBoundingClientRect();
        const elRect = this.$el.getBoundingClientRect();

        const scrollOffset = elRect.top - containerRect.top + container.scrollTop - 50;

        container.scrollTo({
          top: scrollOffset,
          behavior: 'smooth',
        });
      });
    }
  }

  init() {
    if (this.level > 0) {
      window.addEventListener('scroll', this._updatePosition, { passive: true });
      window.addEventListener('resize', this._updatePosition, { passive: true });
    }
  }

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
    console.warn('⚠️ Alpine is not loaded, Dropdown component not registered');
    return;
  }

  window.Alpine.data('dropdown', (config) => new DropdownMenu(config));
  console.log('✅ Dropdown component registered');
}

// Жестко вешаем слушатель на alpine:init
document.addEventListener('alpine:init', () => {
  registerDropdown();
});

export default DropdownMenu;