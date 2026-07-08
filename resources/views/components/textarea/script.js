/**
 * Компонент Textarea для Alpine.js
 * 
 * Многострочное поле ввода с поддержкой:
 * - Валидации (required, minlength, maxlength)
 * - Счётчика символов
 * - Кастомных callbacks (onInput, onBlur)
 * - Accessibility (ARIA-атрибуты)
 */
class TextareaComponent {
  // ============================================================================
  // Конструктор
  // ============================================================================

  constructor(props) {
    this.value = props.value ?? '';
    this.validationRules = props.validationRules ?? {};
    this.validationMessages = props.validationMessages ?? {};
    this.validationMode = props.validationMode ?? 'onblur';
    this.onBlurCallback = props.onBlurCallback ?? null;
    this.onInputCallback = props.onInputCallback ?? null;
    this.serverError = !!props.serverError;
    this.maxlength = props.maxlength ?? null;

    this.validationError = '';
    this.touched = false;
    this.charCount = this.value.length;
    this.validationTimeout = null;
  }

  // ============================================================================
  // Lifecycle
  // ============================================================================

  init() {
    // Watch за изменениями value
    this.$watch('value', () => {
      // Обновляем счётчик символов
      this.charCount = this.value.length;

      // Debounce валидация для oninput режима
      if (this.validationMode === 'oninput' && Object.keys(this.validationRules).length > 0) {
        clearTimeout(this.validationTimeout);
        this.validationTimeout = setTimeout(() => {
          this.validate();
        }, 300);
      }
    });

    // Очистка ресурсов при уничтожении компонента
    if (typeof this.$cleanup === 'function') {
      this.$cleanup(() => this.destroy());
    } else {
      this.$el.addEventListener('alpine:destroy', () => this.destroy());
    }
  }

  destroy() {
    if (this.validationTimeout) {
      clearTimeout(this.validationTimeout);
    }
  }

  // ============================================================================
  // Валидация
  // ============================================================================

  validate() {
    this.touched = true;
    this.validationError = '';

    const valueToCheck = (this.value || '').trim();

    // Required
    if (this.validationRules.required && !valueToCheck) {
      this.validationError =
        this.validationMessages.required || 'Поле обязательно для заполнения';
      return false;
    }

    // Minlength
    if (
      this.validationRules.minlength &&
      valueToCheck.length < this.validationRules.minlength
    ) {
      this.validationError =
        this.validationMessages.minlength ||
        `Минимум ${this.validationRules.minlength} символов`;
      return false;
    }

    // Maxlength
    if (
      this.validationRules.maxlength &&
      valueToCheck.length > this.validationRules.maxlength
    ) {
      this.validationError =
        this.validationMessages.maxlength ||
        `Максимум ${this.validationRules.maxlength} символов`;
      return false;
    }

    return true;
  }

  // ============================================================================
  // Обработчики событий
  // ============================================================================

  handleInput(event) {
    this.value = event.target.value;
    this.charCount = this.value.length;

    // Валидация в режиме onblur (если поле уже было тронуто)
    if (this.touched && this.validationMode === 'onblur') {
      this.validate();
    }

    // Callback onInput
    if (this.onInputCallback) {
      this.evaluateExpression(this.onInputCallback);
    }
  }

  handleBlur() {
    // Очищаем debounce таймер
    if (this.validationTimeout) {
      clearTimeout(this.validationTimeout);
    }

    // Валидация в режиме onblur или change
    if (this.validationMode === 'onblur' || this.validationMode === 'change') {
      this.validate();
    }

    // Callback onBlur
    if (this.onBlurCallback) {
      this.evaluateExpression(this.onBlurCallback);
    }
  }

  handleFormSubmit(event) {
    const form = this.$el.closest('form');
    if (!form || form !== event.target.closest('form')) return;

    this.touched = true;
    if (!this.validate()) {
      event.preventDefault();
      event.stopPropagation();
      this.$refs.textarea?.focus();
    }
  }

  // ============================================================================
  // Утилиты
  // ============================================================================

  evaluateExpression(expr) {
    if (!expr || typeof expr !== 'string') return;
    try {
      const fn = new Function('with(this) { ' + expr + ' }');
      fn.call(this);
    } catch (e) {
      console.warn('Ошибка выполнения callback:', e);
    }
  }
}

// ============================================================================
// Регистрация компонента
// ============================================================================

export function registerTextarea() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine не загружен, компонент Textarea не зарегистрирован');
    return;
  }
  window.Alpine.data('textarea', (props) => new TextareaComponent(props));
  console.log('✅ Textarea компонент зарегистрирован');
}

if (typeof window.Alpine !== 'undefined') {
  registerTextarea();
} else {
  document.addEventListener('alpine:init', () => registerTextarea());
}

export default TextareaComponent;