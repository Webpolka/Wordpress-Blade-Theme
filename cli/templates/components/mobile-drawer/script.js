/**
 * Mobile Drawer Component for Alpine.js
 * 
 * Sliding menu/panel with support for:
 * - Appearance/disappearance animation
 * - Focus trap (keeping focus inside drawer)
 * - Returning focus to trigger on close
 * - Programmatic control via events
 * - Accessibility (ARIA attributes)
 * - Custom burger or trigger slot
 */
class MobileDrawerComponent {
  constructor(props) {
    this.position = props.position ?? 'left';
    this.width = props.width ?? 'w-80';
    this.showOverlay = props.overlay ?? true;
    this.title = props.title ?? null;
    this.drawerName = props.name ?? null;

    this.isOpen = false;
    this.lastTrigger = null;
    
    this._globalListeners = null;
    this._transitionHandler = null;
  }

  init() {
    if (this.drawerName) {
      this.listenToGlobalEvents();
    }

    this.$watch('isOpen', (value) => {
      this.handleStateChange(value);
    });

    if (typeof this.$cleanup === 'function') {
      this.$cleanup(() => this.destroy());
    } else {
      this.$el.addEventListener('alpine:destroy', () => this.destroy());
    }
  }

  destroy() {
    if (this.isOpen) {
      document.body.style.overflow = '';
    }
    
    if (this._globalListeners) {
      window.removeEventListener('drawer-toggle', this._globalListeners.toggle);
      window.removeEventListener('drawer-open', this._globalListeners.open);
      window.removeEventListener('drawer-close', this._globalListeners.close);
      this._globalListeners = null;
    }
    
    if (this._transitionHandler && this.$refs.panel) {
      this.$refs.panel.removeEventListener('transitionend', this._transitionHandler);
    }
  }

  toggle() {
    this.isOpen ? this.close() : this.open();
  }

  open() {
    if (this.isOpen) return;

    this.lastTrigger = document.activeElement;
    
    this.isOpen = true;

    this.$nextTick(() => {
      this.$refs.panel?.focus();
    });

    this.$dispatch('drawer:opened', { name: this.drawerName });
  }

  close() {
    if (!this.isOpen) return;

    this.isOpen = false;

    const panel = this.$refs.panel;
    if (panel) {
      if (this._transitionHandler) {
        panel.removeEventListener('transitionend', this._transitionHandler);
      }
      
      this._transitionHandler = (e) => {
        if (e.propertyName !== 'transform') return;
        panel.removeEventListener('transitionend', this._transitionHandler);
        this._transitionHandler = null;
        
        if (this.lastTrigger && typeof this.lastTrigger.focus === 'function') {
          this.lastTrigger.focus();
        }
        this.lastTrigger = null;
      };
      
      panel.addEventListener('transitionend', this._transitionHandler);
      
      setTimeout(() => {
        if (this._transitionHandler) {
          panel.removeEventListener('transitionend', this._transitionHandler);
          this._transitionHandler = null;
          this.lastTrigger?.focus();
          this.lastTrigger = null;
        }
      }, 400);
    } else {
      this.lastTrigger?.focus();
      this.lastTrigger = null;
    }

    this.$dispatch('drawer:closed', { name: this.drawerName });
  }

  trapFocus(event) {
    const panel = this.$refs.panel;
    if (!panel || !this.isOpen) return;

    const focusable = Array.from(
      panel.querySelectorAll(MobileDrawerComponent.FOCUSABLE)
    );

    if (focusable.length === 0) return;

    const first = focusable[0];
    const last = focusable[focusable.length - 1];

    if (event.shiftKey) {
      if (document.activeElement === first) {
        event.preventDefault();
        last.focus();
      }
    } else {
      if (document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    }
  }

  listenToGlobalEvents() {
    this._globalListeners = {
      toggle: (e) => {
        if (e.detail === this.drawerName) this.toggle();
      },
      open: (e) => {
        if (e.detail === this.drawerName) this.open();
      },
      close: (e) => {
        if (e.detail === this.drawerName) this.close();
      },
    };

    window.addEventListener('drawer-toggle', this._globalListeners.toggle);
    window.addEventListener('drawer-open', this._globalListeners.open);
    window.addEventListener('drawer-close', this._globalListeners.close);
  }

  handleStateChange(isOpen) {
    document.body.style.overflow = isOpen ? 'hidden' : '';

    const trigger = this.$el.querySelector('[data-drawer-trigger-element]');
    if (trigger) {
      trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }
  }

  handleEscape() {
    if (this.isOpen) {
      this.close();
    }
  }

  handleTab(event) {
    if (this.isOpen) {
      this.trapFocus(event);
    }
  }
}

MobileDrawerComponent.FOCUSABLE = `
  button:not([disabled]), 
  [href]:not([tabindex="-1"]), 
  input:not([type="hidden"]):not([disabled]), 
  select:not([disabled]), 
  textarea:not([disabled]), 
  [tabindex]:not([tabindex="-1"]):not([disabled]),
  [contenteditable="true"],
  details > summary,
  audio[controls],
  video[controls]
`.replace(/\s+/g, ' ').trim();

export function registerDrawer() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine is not loaded, MobileDrawer component not registered');
    return;
  }
  window.Alpine.data('drawer', (props) => new MobileDrawerComponent(props));
  console.log('✅ MobileDrawer component registered');
}

//  Жестко вешаем слушатель на alpine:init
document.addEventListener('alpine:init', () => {
  registerDrawer();
});

export default MobileDrawerComponent;