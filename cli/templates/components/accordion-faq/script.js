import AlpineCollapse from '@alpinejs/collapse';

export function registerAccordionFaq() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine is not loaded, AccordionFaq component not registered');
    return;
  }

  // Регистрируем плагин один раз, внутри функции
  window.Alpine.plugin(AlpineCollapse);

  window.Alpine.data('accordionFaq', (props = {}) => ({
    openIds: [],
    multiple: props.multiple ?? false,
    firstOpen: props.firstOpen ?? false,

    init() {
      // Слушаем регистрацию дочерних элементов
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

  console.log('✅ AccordionFaq component registered');
}

// МАГИЯ FIX: Жестко вешаем слушатель на alpine:init
document.addEventListener('alpine:init', () => {
  registerAccordionFaq();
});