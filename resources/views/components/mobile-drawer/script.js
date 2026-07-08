/**
 * Компонент Mobile Drawer для Alpine.js
 * 
 * Выдвижное меню/панель с поддержкой:
 * - Анимации появления/исчезновения
 * - Focus trap (удержание фокуса внутри drawer)
 * - Возврата фокуса к триггеру при закрытии
 * - Программного управления через events
 * - Accessibility (ARIA-атрибуты)
 * - Кастомного бургера или слота trigger
 */
class MobileDrawerComponent {
  // ============================================================================
  // Конструктор
  // ============================================================================

  constructor(props) {
    this.position = props.position ?? 'left';
    this.width = props.width ?? 'w-80';
    this.showOverlay = props.overlay ?? true;
    this.title = props.title ?? null;
    this.drawerName = props.name ?? null;

    // Состояние UI
    this.isOpen = false;
    this.lastTrigger = null;
    
    // Для cleanup listeners
    this._globalListeners = null;
    this._transitionHandler = null;
  }

  // ============================================================================
  // Lifecycle
  // ============================================================================

  init() {
    // Если есть name - слушаем глобальные события управления
    if (this.drawerName) {
      this.listenToGlobalEvents();
    }

    // Следим за isOpen - обновляем ARIA и блокируем скролл body
    this.$watch('isOpen', (value) => {
      this.handleStateChange(value);
    });

    // Очистка ресурсов при уничтожении компонента
    if (typeof this.$cleanup === 'function') {
      this.$cleanup(() => this.destroy());
    } else {
      this.$el.addEventListener('alpine:destroy', () => this.destroy());
    }
  }

  destroy() {
    // Разблокируем скролл если drawer был открыт
    if (this.isOpen) {
      document.body.style.overflow = '';
    }
    
    // Удаляем глобальные listeners
    if (this._globalListeners) {
      window.removeEventListener('drawer-toggle', this._globalListeners.toggle);
      window.removeEventListener('drawer-open', this._globalListeners.open);
      window.removeEventListener('drawer-close', this._globalListeners.close);
      this._globalListeners = null;
    }
    
    // Удаляем transition handler
    if (this._transitionHandler && this.$refs.panel) {
      this.$refs.panel.removeEventListener('transitionend', this._transitionHandler);
    }
  }

  // ============================================================================
  // Публичные методы (вызываются из blade через @click)
  // ============================================================================

  toggle() {
    this.isOpen ? this.close() : this.open();
  }

  open() {
    if (this.isOpen) return;

    // Запоминаем текущий активный элемент для возврата фокуса
    this.lastTrigger = document.activeElement;
    
    this.isOpen = true;

    // Следующий тик - когда DOM обновится, ставим фокус на панель
    this.$nextTick(() => {
      this.$refs.panel?.focus();
    });

    // Событие открытия
    this.$dispatch('drawer:opened', { name: this.drawerName });
  }

  close() {
    if (!this.isOpen) return;

    this.isOpen = false;

    // Возвращаем фокус к триггеру после завершения анимации
    const panel = this.$refs.panel;
    if (panel) {
      // Удаляем старый handler если есть
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
      
      // Fallback если transitionend не сработает (например, анимация отключена)
      setTimeout(() => {
        if (this._transitionHandler) {
          panel.removeEventListener('transitionend', this._transitionHandler);
          this._transitionHandler = null;
          this.lastTrigger?.focus();
          this.lastTrigger = null;
        }
      }, 400);
    } else {
      // Если panel нет, просто возвращаем фокус
      this.lastTrigger?.focus();
      this.lastTrigger = null;
    }

    // Событие закрытия
    this.$dispatch('drawer:closed', { name: this.drawerName });
  }

  // ============================================================================
  // Focus Trap (Tab внутри drawer)
  // ============================================================================

  trapFocus(event) {
    const panel = this.$refs.panel;
    if (!panel || !this.isOpen) return;

    const focusable = Array.from(
      panel.querySelectorAll(MobileDrawerComponent.FOCUSABLE)  // ← ИСПРАВЛЕНО!
    );

    if (focusable.length === 0) return;

    const first = focusable[0];
    const last = focusable[focusable.length - 1];

    if (event.shiftKey) {
      // Shift+Tab: с последнего идём на первый
      if (document.activeElement === first) {
        event.preventDefault();
        last.focus();
      }
    } else {
      // Tab: с первого идём на последний
      if (document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    }
  }

  // ============================================================================
  // Глобальные события управления (если указан name)
  // ============================================================================

  listenToGlobalEvents() {
    // Сохраняем bound функции для возможности removeEventListener
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

  // ============================================================================
  // Вспомогательные методы
  // ============================================================================

  /**
   * Обновляет ARIA-атрибуты и блокирует скролл body
   */
  handleStateChange(isOpen) {
    // Блокируем/разблокируем скролл body
    document.body.style.overflow = isOpen ? 'hidden' : '';

    // Обновляем aria-expanded на триггере
    const trigger = this.$el.querySelector('[data-drawer-trigger-element]');
    if (trigger) {
      trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }
  }

  /**
   * Обработчик Escape (вызывается из x-on:keydown.escape.window)
   */
  handleEscape() {
    if (this.isOpen) {
      this.close();
    }
  }

  /**
   * Обработчик Tab (вызывается из x-on:keydown.tab)
   */
  handleTab(event) {
    if (this.isOpen) {
      this.trapFocus(event);
    }
  }
}

// ============================================================================
// Статические свойства
// ============================================================================

/** Селектор интерактивных элементов для focus trap */
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

// ============================================================================
// Регистрация компонента
// ============================================================================

/** Регистрация компонента drawer в Alpine */
export function registerDrawer() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine не загружен, компонент Drawer не зарегистрирован');
    return;
  }
  window.Alpine.data('drawer', (props) => new MobileDrawerComponent(props));
  console.log('✅ MobileDrawer компонент зарегистрирован');
}

// Авто-регистрация
if (typeof window.Alpine !== 'undefined') {
  registerDrawer();
} else {
  document.addEventListener('alpine:init', () => registerDrawer());
}

export default MobileDrawerComponent;