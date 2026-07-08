import ModalContentFactory from "./modalContentFactory";
/**
 * Компонент Modal на чистом JS
 * Singleton паттерн - гарантируем один экземпляр
 * 
 * Управляет двумя типами модалок:
 * - Обычные (x-modal) - через data-modal-target / data-modal-close
 * - Динамическая (x-modal-dynamic) - через window.modalManager.openDynamic()
 * 
 * @example
 *   // Обычная модалка
 *   window.modalManager.open('user-modal');
 *   window.modalManager.close('user-modal');
 * 
 *   // Динамическая модалка
 *   window.modalManager.openDynamic({
 *     title: 'Заголовок',
 *     content: '<p>Контент</p>',
 *     size: 'md'
 *   });
 *   window.modalManager.closeDynamic();
 */
class ModalManager {
  constructor() {
    // Singleton: если уже есть экземпляр - возвращаем его
    if (ModalManager.instance) {
      return ModalManager.instance;
    }

    this.modals = new Map();
    this.baseZIndex = 50;
    this.openStack = [];
    this.triggerStack = [];
    this.scanTimeout = null;
    this.observer = null;
    
    // Динамическая модалка
    this.dynamicModal = null;
    this.dynamicOnClose = null;
    
    this.init();

    // Сохраняем экземпляр
    ModalManager.instance = this;
  }

  static FOCUSABLE = `
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

  init() {
    this.scanModals();
    this.initDynamicModal();

    // MutationObserver с throttle
    this.observer = new MutationObserver(() => {
      clearTimeout(this.scanTimeout);
      this.scanTimeout = setTimeout(() => {
        this.scanModals();
        this.initDynamicModal();
      }, 100);
    });
    this.observer.observe(document.body, { childList: true, subtree: true });

    // Клик
    document.addEventListener('click', (e) => {
      // Открытие
      const trigger = e.target.closest('[data-modal-target]');
      if (trigger) {
        e.preventDefault();
        this.triggerStack.push(trigger);
        this.open(trigger.dataset.modalTarget);
        return;
      }

      // Закрытие
      const closer = e.target.closest('[data-modal-close]');
      if (closer) {
        const modal = closer.closest('[data-modal], [data-modal-dynamic]');
        if (modal) {
          if (modal.hasAttribute('data-modal-dynamic')) {
            this.closeDynamic();
          } else {
            this.close(modal.dataset.modal);
          }
        }
      }
    });

    // Клавиатура
    document.addEventListener('keydown', (e) => {
      const topModal = this.getTopModal();
      if (!topModal) return;

      if (e.key === 'Escape' && topModal.dataset.closeOnEscape !== 'false') {
        e.preventDefault();
        if (topModal.hasAttribute('data-modal-dynamic')) {
          this.closeDynamic();
        } else {
          this.close(topModal.dataset.modal);
        }
      }

      if (e.key === 'Tab') {
        this.trapFocus(e, topModal);
      }
    });
  }

  initDynamicModal() {
    const dynamicModal = document.querySelector('[data-modal-dynamic]');
    if (dynamicModal && !this.dynamicModal) {
      this.dynamicModal = dynamicModal;
      this.setupModal(dynamicModal, true);
    }
  }

  scanModals() {
    document.querySelectorAll('[data-modal]').forEach((modal) => {
      const name = modal.dataset.modal;
      if (!name || this.modals.has(name)) return;
      this.modals.set(name, modal);
      this.setupModal(modal, false);
    });
  }

  setupModal(modal, isDynamic = false) {
    // Overlay click
    modal.addEventListener('click', (e) => {
      if (e.target === modal && modal.dataset.closeOnOverlay !== 'false') {
        if (isDynamic) {
          this.closeDynamic();
        } else {
          this.close(modal.dataset.modal);
        }
      }
    });

    // Transitionend для точного скрытия
    const dialog = modal.querySelector('[role="dialog"]');
    if (dialog) {
      dialog.addEventListener('transitionend', (e) => {
        if (e.target !== dialog) return;
        if (e.propertyName !== 'opacity') return;
        
        if (!modal.classList.contains('modal-overlay-visible') &&
            !dialog.classList.contains('modal-dialog-visible')) {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
        }
      });
    }
  }

  open(name) {
    const modal = this.modals.get(name);
    if (!modal || !modal.classList.contains('hidden')) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    this.openStack.push(modal);
    modal.style.zIndex = this.baseZIndex + this.openStack.length;

    requestAnimationFrame(() => {
      modal.classList.add('modal-overlay-visible');
      
      const dialog = modal.querySelector('[role="dialog"]');
      if (dialog) {
        dialog.classList.add('modal-dialog-visible');
        
        requestAnimationFrame(() => {
          const focusable = dialog.querySelector(ModalManager.FOCUSABLE);
          (focusable || dialog).focus();
        });
      }
    });

    document.body.style.overflow = 'hidden';

    modal.dispatchEvent(new CustomEvent('modal:opened', { 
      detail: { name }, 
      bubbles: true 
    }));
  }

  close(name) {
    const modal = this.modals.get(name);
    if (!modal || modal.classList.contains('hidden')) return;

    this.openStack = this.openStack.filter(m => m !== modal);
    const trigger = this.triggerStack.pop();

    modal.classList.remove('modal-overlay-visible');
    
    const dialog = modal.querySelector('[role="dialog"]');
    if (dialog) {
      dialog.classList.remove('modal-dialog-visible');

      const handler = (e) => {
        if (e.propertyName !== 'opacity') return;
        dialog.removeEventListener('transitionend', handler);
        trigger?.focus();
      };
      dialog.addEventListener('transitionend', handler);
    }

    this.openStack.forEach((m, i) => {
      m.style.zIndex = this.baseZIndex + i + 1;
    });

    if (this.openStack.length === 0) {
      document.body.style.overflow = '';
    }

    modal.dispatchEvent(new CustomEvent('modal:closed', { 
      detail: { name }, 
      bubbles: true 
    }));
  }

  openDynamic({
    title = '',
    content = '',
    size = 'md',
    closeOnOverlay = true,
    closeOnEscape = true,
    showCloseButton = true,
    onClose = null,
  } = {}) {
    if (!this.dynamicModal) {
      console.warn('Динамическая модалка не найдена. Добавьте <x-modal-dynamic /> в лейаут.');
      return;
    }

    const modal = this.dynamicModal;
    const dialog = modal.querySelector('[role="dialog"]');
    const titleEl = dialog.querySelector('#modal-dynamic-title');
    const contentEl = dialog.querySelector('[role="document"]');
    const closeBtn = dialog.querySelector('[data-modal-close]');

    const sizes = {
      'sm': 'max-w-sm',
      'md': 'max-w-md',
      'lg': 'max-w-lg',
      'xl': 'max-w-xl',
      '2xl': 'max-w-2xl',
      'full': 'max-w-full mx-4',
    };
    const sizeClass = sizes[size] || sizes['md'];

    dialog.className = dialog.className.replace(/max-w-\S+/g, '');
    dialog.classList.add(sizeClass);

    if (titleEl) {
      titleEl.textContent = title;
    }

    if (contentEl) {
      contentEl.innerHTML = content;
    }

    if (closeBtn) {
      closeBtn.style.display = showCloseButton ? '' : 'none';
    }

    modal.dataset.closeOnOverlay = closeOnOverlay ? 'true' : 'false';
    modal.dataset.closeOnEscape = closeOnEscape ? 'true' : 'false';

    this.dynamicOnClose = onClose;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    this.openStack.push(modal);
    modal.style.zIndex = this.baseZIndex + this.openStack.length;

    requestAnimationFrame(() => {
      modal.classList.add('modal-overlay-visible');
      
      if (dialog) {
        dialog.classList.add('modal-dialog-visible');
        
        requestAnimationFrame(() => {
          const focusable = dialog.querySelector(ModalManager.FOCUSABLE);
          (focusable || dialog).focus();
        });
      }
    });

    document.body.style.overflow = 'hidden';

    modal.dispatchEvent(new CustomEvent('modal:opened', { 
      detail: { name: 'dynamic' }, 
      bubbles: true 
    }));
  }

  closeDynamic() {
    if (!this.dynamicModal || this.dynamicModal.classList.contains('hidden')) return;

    const modal = this.dynamicModal;
    this.openStack = this.openStack.filter(m => m !== modal);
    const trigger = this.triggerStack.pop();

    modal.classList.remove('modal-overlay-visible');
    
    const dialog = modal.querySelector('[role="dialog"]');
    if (dialog) {
      dialog.classList.remove('modal-dialog-visible');

      const handler = (e) => {
        if (e.propertyName !== 'opacity') return;
        dialog.removeEventListener('transitionend', handler);
        trigger?.focus();
      };
      dialog.addEventListener('transitionend', handler);
    }

    this.openStack.forEach((m, i) => {
      m.style.zIndex = this.baseZIndex + i + 1;
    });

    if (this.openStack.length === 0) {
      document.body.style.overflow = '';
    }

    if (typeof this.dynamicOnClose === 'function') {
      this.dynamicOnClose();
      this.dynamicOnClose = null;
    }

    modal.dispatchEvent(new CustomEvent('modal:closed', { 
      detail: { name: 'dynamic' }, 
      bubbles: true 
    }));
  }

  getTopModal() {
    return this.openStack[this.openStack.length - 1] || null;
  }

  trapFocus(e, modal) {
    const dialog = modal.querySelector('[role="dialog"]');
    if (!dialog) return;

    const focusable = Array.from(dialog.querySelectorAll(ModalManager.FOCUSABLE));
    if (focusable.length === 0) return;

    const first = focusable[0];
    const last = focusable[focusable.length - 1];

    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  }

  destroy() {
    this.observer?.disconnect();
    this.modals.clear();
    this.openStack = [];
    this.triggerStack = [];
    document.body.style.overflow = '';
    ModalManager.instance = null;
  }
}
// ============================================================================
// Singleton инициализация
// ============================================================================

if (typeof window !== 'undefined') {
  // ModalManager - экземпляр
  if (!window.modalManager) {
    window.modalManager = new ModalManager();
    console.log('✅ ModalManager инициализирован');
  }

  // ModalContentFactory - САМ КЛАСС (не экземпляр!)
  if (!window.ModalContentFactory) {
    window.ModalContentFactory = ModalContentFactory; // БЕЗ new!
    console.log('✅ ModalContentFactory инициализирован');
  }
}


export default ModalManager;