/**
 * Компоненты Select и ChipsDrag для Alpine.js
 * 
 * Select - универсальный выпадающий список с одиночным/множественным выбором,
 *          поиском, иконками, клавиатурной навигацией.
 * 
 * ChipsDrag - drag-scroll контейнер для чипсов в мультиселекте.
 */

// ============================================================================
// SelectComponent
// ============================================================================

/**
 * Компонент Select для Alpine.js.
 * Поддерживает одиночный и множественный выбор, поиск, иконки, клавиатуру.
 */
class SelectComponent {
  constructor(props) {
    this.options = props.options || [];
    this.value = props.multiple
      ? Array.isArray(props.value) ? props.value : []
      : (props.value ?? null);
    this.multiple = props.multiple || false;
    this.searchable = props.searchable ?? true;
    this.placeholder = props.placeholder || 'Выберите...';
    this.disabled = props.disabled || false;
    this.required = props.required || false;
    this.validationRules = props.validationRules || {};
    this.validationMessages = props.validationMessages || {};
    this.validationMode = props.validationMode || 'onblur';
    this.onBlurCallback = props.onBlurCallback || null;
    this.onInputCallback = props.onInputCallback || null;

    this.isOpen = false;
    this.searchQuery = '';
    this.highlightedIndex = 0;
    this.validationError = '';
    this.touched = false;
    this.maxChips = props.maxChips ?? 3;
  }

  init() {
    if (this.validationRules && Object.keys(this.validationRules).length > 0) {
      this.$watch('value', () => {
        if (this.validationMode === 'oninput') this.validate();
      });
    }
  }

  // ----------------------------------------------------------------------------
  // Dispatch событий
  // ----------------------------------------------------------------------------

  dispatchInputEvent() {
    this.$el.dispatchEvent(
      new CustomEvent('input', {
        bubbles: true,
        detail: this.value,
      }),
    );
  }

  // ----------------------------------------------------------------------------
  // Getters
  // ----------------------------------------------------------------------------

  get selectedOptions() {
    if (this.multiple) {
      return this.options.filter((o) => (this.value || []).includes(o.value));
    } else {
      return this.options.filter((o) => o.value === this.value);
    }
  }

  get selectedLabel() {
    const found = this.options.find((o) => o.value === this.value);
    return found ? found.label : '';
  }

  get filteredOptions() {
    if (!this.searchQuery) return this.options;
    const query = this.searchQuery.toLowerCase();
    return this.options.filter((o) => o.label.toLowerCase().includes(query));
  }

  // ----------------------------------------------------------------------------
  // Проверки
  // ----------------------------------------------------------------------------

  isSelected(opt) {
    if (this.multiple) {
      return (this.value || []).some((v) => v == opt.value);
    } else {
      return this.value == opt.value;
    }
  }

  // ----------------------------------------------------------------------------
  // Управление состоянием
  // ----------------------------------------------------------------------------

  toggle() {
    if (this.disabled) return;
    this.isOpen = !this.isOpen;
    if (this.isOpen) {
      this.highlightedIndex = 0;
      this.validationError = '';
    }
  }

  close() {
    this.isOpen = false;
  }

  selectOption(opt) {
    if (this.disabled) return;
    
    if (this.multiple) {
      const current = this.value || [];
      this.value = current.includes(opt.value)
        ? current.filter((v) => v !== opt.value)
        : [...current, opt.value];
    } else {
      this.value = opt.value;
      this.close();
      this.$refs.trigger?.focus();
    }
    
    this.searchQuery = '';
    this.validate();
    this.dispatchInputEvent();

    if (this.onInputCallback) {
      this.evaluateExpression(this.onInputCallback);
    }
  }

  removeOption(val) {
    if (!this.multiple) return;
    const current = this.value || [];
    this.value = current.filter((v) => v !== val);
    this.validate();
    this.dispatchInputEvent();

    if (this.onInputCallback) {
      this.evaluateExpression(this.onInputCallback);
    }
  }

  clear() {
    this.value = this.multiple ? [] : null;
    this.validate();
    this.dispatchInputEvent();

    if (this.onInputCallback) {
      this.evaluateExpression(this.onInputCallback);
    }
  }

  // ----------------------------------------------------------------------------
  // Валидация
  // ----------------------------------------------------------------------------

  validate() {
    this.touched = true;
    this.validationError = '';

    const valueToCheck = this.multiple ? (this.value || []).length : this.value;
    
    if (this.validationRules.required) {
      if (this.multiple) {
        if (!valueToCheck || valueToCheck === 0) {
          this.validationError =
            this.validationMessages.required || 'Выберите хотя бы один вариант';
          return false;
        }
      } else {
        if (
          valueToCheck === null ||
          valueToCheck === undefined ||
          valueToCheck === ''
        ) {
          this.validationError =
            this.validationMessages.required || 'Обязательное поле';
          return false;
        }
      }
    }
    
    return true;
  }

  handleBlur() {
    if (this.validationMode === 'onblur' || this.validationMode === 'change') {
      this.validate();
    }
    if (this.onBlurCallback) {
      this.evaluateExpression(this.onBlurCallback);
    }
  }

  // ----------------------------------------------------------------------------
  // Обработка выражений
  // ----------------------------------------------------------------------------

  evaluateExpression(expr) {
    if (!expr || typeof expr !== 'string') return;
    try {
      const fn = new Function('with(this) { ' + expr + ' }');
      fn.call(this);
    } catch (e) {
      console.warn('❌ Ошибка выполнения callback:', e);
    }
  }

  // ----------------------------------------------------------------------------
  // Обработка формы
  // ----------------------------------------------------------------------------

  handleFormSubmit(event) {
    const form = this.$el.closest('form');
    if (!form || form !== event.target.closest('form')) return;

    this.touched = true;
    if (!this.validate()) {
      event.preventDefault();
      event.stopPropagation();
      this.$refs.trigger?.focus();
    }
  }

  // ----------------------------------------------------------------------------
  // Клавиатурная навигация
  // ----------------------------------------------------------------------------

  handleKeydown(event) {
    if (this.disabled) return;
    
    const key = event.key;
    const isSearchFocused =
      this.$refs.searchInput &&
      document.activeElement === this.$refs.searchInput;

    // Быстрый поиск по первым символам
    if (
      this.isOpen &&
      !isSearchFocused &&
      key.length === 1 &&
      key !== ' ' &&
      !event.ctrlKey &&
      !event.altKey &&
      !event.metaKey
    ) {
      if (this.searchable) {
        event.preventDefault();
        const searchInput = this.$refs.searchInput;
        if (searchInput) {
          searchInput.focus();
          const currentValue = this.searchQuery || '';
          this.searchQuery = currentValue + key;
          setTimeout(() => {
            searchInput.selectionStart = searchInput.selectionEnd =
              this.searchQuery.length;
          }, 0);
        }
      }
      return;
    }

    // Навигация по списку
    if (this.isOpen) {
      const total = this.filteredOptions.length;
      if (total === 0) return;

      switch (key) {
        case 'ArrowDown':
          event.preventDefault();
          this.highlightedIndex = Math.min(this.highlightedIndex + 1, total - 1);
          this.scrollToHighlighted();
          break;
          
        case 'ArrowUp':
          event.preventDefault();
          this.highlightedIndex = Math.max(this.highlightedIndex - 1, 0);
          this.scrollToHighlighted();
          break;
          
        case 'Enter':
        case ' ':
        case 'Spacebar':
          event.preventDefault();
          const selectedOpt = this.filteredOptions[this.highlightedIndex];
          if (selectedOpt) {
            this.selectOption(selectedOpt);
          }
          break;
          
        case 'Escape':
          this.close();
          break;
          
        case 'Tab':
          this.close();
          break;
          
        case 'Home':
          event.preventDefault();
          this.highlightedIndex = 0;
          this.scrollToHighlighted();
          break;
          
        case 'End':
          event.preventDefault();
          this.highlightedIndex = total - 1;
          this.scrollToHighlighted();
          break;
      }
    } else {
      // Открытие списка
      if (
        key === 'Enter' ||
        key === ' ' ||
        key === 'Spacebar' ||
        key === 'ArrowDown'
      ) {
        event.preventDefault();
        this.toggle();
      }
    }
  }

  scrollToHighlighted() {
    const container = this.$refs.listbox;
    if (!container) return;
    const items = container.querySelectorAll('[role="option"]');
    if (items[this.highlightedIndex]) {
      items[this.highlightedIndex].scrollIntoView({ block: 'nearest' });
    }
  }
}

// ============================================================================
// ChipsDragComponent
// ============================================================================

/**
 * Компонент для drag-scroll контейнера чипсов.
 * Поддерживает перетаскивание мышью и тач-события.
 */
class ChipsDragComponent {
  constructor(props) {
    this.isDragging = false;
    this.startX = 0;
    this.scrollLeft = 0;
    this.canScroll = false;
  }

  init() {
    this.$nextTick(() => {
      const container = this.$refs.chipsContainer;
      if (!container) return;
      
      this.updateCanScroll();
      
      // Следим за изменениями размера контейнера
      const resizeObserver = new ResizeObserver(() => {
        this.updateCanScroll();
      });
      resizeObserver.observe(container);
      
      // Следим за изменениями дочерних элементов
      const mutationObserver = new MutationObserver(() => {
        this.$nextTick(() => this.updateCanScroll());
      });
      mutationObserver.observe(container, { 
        childList: true, 
        subtree: true,
        characterData: true
      });
    });
  }

  updateCanScroll() {
    const container = this.$refs.chipsContainer;
    if (!container) return;
    this.canScroll = container.scrollWidth > container.clientWidth;
  }

  startDrag(e) {
    if (!this.canScroll) return;
    const container = this.$refs.chipsContainer;
    if (!container) return;
    
    this.isDragging = true;
    this.startX = e.pageX || e.touches[0].pageX;
    this.scrollLeft = container.scrollLeft;
    container.style.cursor = 'grabbing';
    container.style.userSelect = 'none';
  }

  moveDrag(e) {
    if (!this.isDragging) return;
    e.preventDefault();
    
    const container = this.$refs.chipsContainer;
    if (!container) return;
    
    const x = e.pageX || e.touches[0].pageX;
    const walk = (x - this.startX) * 1.5;
    container.scrollLeft = this.scrollLeft - walk;
  }

  endDrag() {
    if (!this.isDragging) return;
    
    const container = this.$refs.chipsContainer;
    if (container) {
      container.style.cursor = '';
      container.style.userSelect = '';
    }
    this.isDragging = false;
  }
}

// ============================================================================
// Регистрация компонентов
// ============================================================================

/** Регистрация компонента select в Alpine */
export function registerSelect() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine не загружен, компонент Select не зарегистрирован');
    return;
  }
  window.Alpine.data('select', (props) => new SelectComponent(props));
  console.log('✅ Select компонент зарегистрирован');
}

/** Регистрация компонента chipsDrag в Alpine */
export function registerChipsDrag() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine не загружен, компонент ChipsDrag не зарегистрирован');
    return;
  }
  window.Alpine.data('chipsDrag', (props) => new ChipsDragComponent(props));
  console.log('✅ ChipsDrag компонент зарегистрирован');
}

// ============================================================================
// Авто-регистрация
// ============================================================================

if (typeof window.Alpine !== 'undefined') {
  registerSelect();
  registerChipsDrag();
} else {
  document.addEventListener('alpine:init', () => {
    registerSelect();
    registerChipsDrag();
  });
}

// ============================================================================
// Экспорт
// ============================================================================

export default SelectComponent;