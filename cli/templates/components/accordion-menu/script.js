import AlpineCollapse from '@alpinejs/collapse';

export function registerAccordionMenu() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine is not loaded, AccordionMenu component not registered');
    return;
  }

  // Регистрируем плагин один раз, внутри функции
  window.Alpine.plugin(AlpineCollapse);

  window.Alpine.data('accordionMenu', (props = {}) => ({
    activeItem: props.activeItem ?? null,
    collapsed: props.collapsed ?? false,
    init() {}
  }));

  window.Alpine.data('accordionSubmenu', (props = {}) => ({
    isOpen: props.open ?? false,
    label: props.label ?? '',
    init() {},
    toggle() {
      this.isOpen = !this.isOpen;
    },
    open() {
      this.isOpen = true;
    },
    close() {
      this.isOpen = false;
    }
  }));
  
  console.log('✅ AccordionMenu component registered');
}

// МАГИЯ FIX: Жестко вешаем слушатель на alpine:init
document.addEventListener('alpine:init', () => {
  registerAccordionMenu();
});