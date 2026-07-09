import AlpineCollapse from '@alpinejs/collapse';

/**
 * Компонент Accordion Menu для Alpine.js
 * 
 * Меню с вложенностью (аккордеон).
 * - Раскрытие/скрытие подменю
 * - Поддержка иконок
 * - Активное состояние
 * - Многоуровневая вложенность
 * - Плавная анимация
 */

// Регистрируем плагин, если Alpine уже загружен
if (typeof window.Alpine !== 'undefined') {
  window.Alpine.plugin(AlpineCollapse);
}

export function registerAccordionMenu() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine не загружен, компонент AccordionMenu не зарегистрирован');
    return;
  }

  // Обязательно регистрируем плагин при инициализации Alpine
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
  
  console.log('✅ AccordionMenu компонент зарегистрирован');
}

// Авто-регистрация
if (typeof window.Alpine !== 'undefined') {
  registerAccordionMenu();
} else {
  document.addEventListener('alpine:init', () => registerAccordionMenu());
}