import AlpineCollapse from '@alpinejs/collapse';

// Регистрируем плагин, если Alpine уже загружен
if (typeof window.Alpine !== 'undefined') {
  window.Alpine.plugin(AlpineCollapse);
}

export function registerAccordionFaq() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine не загружен, компонент FaqAccordion не зарегистрирован');
    return;
  }

  // Обязательно регистрируем плагин при инициализации Alpine
  window.Alpine.plugin(AlpineCollapse);

  window.Alpine.data('accordionFaq', (props = {}) => ({
    openIds: [],
    multiple: props.multiple ?? false,
    firstOpen: props.firstOpen ?? false,

    init() {
      // Слушаем регистрацию дочерних элементов (заменяет @faq-register в HTML)
      this.$el.addEventListener('faq-register', (e) => {
        // Если firstOpen=true и пока ни один элемент не открыт, открываем первый пришедший
        if (this.firstOpen && this.openIds.length === 0) {
          this.openIds.push(e.detail.id);
        }
      });
    },

    toggle(id) {
      if (this.multiple) {
        // В режиме multiple: добавляем/удаляем ID из массива
        this.openIds = this.openIds.includes(id) 
          ? this.openIds.filter(i => i !== id) 
          : [...this.openIds, id];
      } else {
        // В обычном режиме: если ID уже открыт - закрываем. Если закрыт - закрываем все, открываем этот.
        this.openIds = this.openIds.includes(id) ? [] : [id];
      }
    },

    isOpen(id) {
      return this.openIds.includes(id);
    }
  }));

  console.log('✅ AccordionFaq компонент зарегистрирован');
}

// Авто-регистрация
if (typeof window.Alpine !== 'undefined') {
  registerAccordionFaq();
} else {
  document.addEventListener('alpine:init', () => registerAccordionFaq());
}