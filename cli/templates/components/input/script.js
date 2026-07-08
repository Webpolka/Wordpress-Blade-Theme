/**
 * Компонент Input для Alpine.js
 * 
 * Универсальное поле ввода с поддержкой:
 * - Валидации (required, email, minlength, maxlength, pattern)
 * - Масок (IMask)
 * - Переключателя видимости пароля
 * - Очистки поля
 * - Кастомных callbacks (onInput, onBlur)
 * - Accessibility (ARIA-атрибуты)
 */
class InputComponent {
  // ============================================================================
  // Конструктор
  // ============================================================================

  constructor(props) {
    // Основные props
    this.value = props.value ?? '';
    this.validationRules = props.validationRules ?? {};
    this.validationMessages = props.validationMessages ?? {};
    this.validationMode = props.validationMode ?? 'onblur';
    this.isPassword = props.isPassword ?? false;
    this.type = props.type ?? 'text';
    this.maxLengthAttr = props.maxLengthAttr ?? null;
    this.onBlurCallback = props.onBlurCallback ?? null;
    this.onInputCallback = props.onInputCallback ?? null;
    this.hasMask = props.hasMask ?? false;
    this.serverError = !!props.serverError;

    // Компиляция regex паттерна (один раз)
    this.compiledPatterns = {};
    if (props.validationRules?.pattern) {
      try {
        this.compiledPatterns.pattern = new RegExp(props.validationRules.pattern);
      } catch (e) {
        console.warn('Невалидный pattern:', props.validationRules.pattern, e);
      }
    }

    // Валидатор email (переиспользуемый элемент)
    this.emailValidator = document.createElement('input');
    this.emailValidator.type = 'email';

    // Состояние UI
    this.validationError = '';
    this.touched = false;
    this.showPassword = false;
    this.isMaskFilled = false;

    // Инстансы (для очистки)
    this.maskInstance = null;
    this.validationTimeout = null;
  }

  // ============================================================================
  // Lifecycle
  // ============================================================================

  init() {
    // Debounce валидация для oninput режима
    if (this.validationRules && Object.keys(this.validationRules).length > 0) {
      this.$watch('value', () => {
        if (this.validationMode === 'oninput') {
          clearTimeout(this.validationTimeout);
          this.validationTimeout = setTimeout(() => {
            this.validate();
          }, 300);
        }
      });
    }

    // Инициализация маски
    this.$nextTick(() => {
      this.initMask();
    });

    // Очистка ресурсов при уничтожении компонента
    if (typeof this.$cleanup === 'function') {
      this.$cleanup(() => this.destroy());
    } else {
      // Fallback для старых версий Alpine
      this.$el.addEventListener('alpine:destroy', () => this.destroy());
    }
  }

  destroy() {
    if (this.validationTimeout) {
      clearTimeout(this.validationTimeout);
    }
    if (this.maskInstance) {
      this.maskInstance.destroy();
      this.maskInstance = null;
    }
  }

  // ============================================================================
  // Маска (IMask)
  // ============================================================================

  initMask() {
    const input = this.$refs.input;
    if (!input) return;

    const mask = input.getAttribute('mask');
    if (!mask) return;

    if (!window.IMask) {
      console.warn('IMask не загружен, маска не будет работать');
      return;
    }

    // Уничтожаем предыдущий инстанс если есть
    if (this.maskInstance) {
      this.maskInstance.destroy();
    }

    this.maskInstance = window.IMask(input, {
      mask: mask,
      lazy: false,
      ...(this.maxLengthAttr ? { maxLength: this.maxLengthAttr } : {}),
    });

    this.maskInstance.on('accept', () => {
      this.value = this.maskInstance.value;
      this.isMaskFilled = this.maskInstance.unmaskedValue.length > 0;
    });

    this.isMaskFilled = this.maskInstance.unmaskedValue.length > 0;
  }

  // ============================================================================
  // Валидация
  // ============================================================================

  validate() {
    this.touched = true;
    this.validationError = '';

    // Получаем значение для проверки
    let valueToCheck = this.value || '';
    if (this.maskInstance) {
      valueToCheck = this.maskInstance.unmaskedValue || '';
    } else if (this.type !== 'password') {
      valueToCheck = valueToCheck.trim();
    }

    // Required
    if (this.validationRules.required && !valueToCheck) {
      this.validationError =
        this.validationMessages.required || 'Поле обязательно для заполнения';
      return false;
    }

    // Email (через HTML5 валидацию)
    if (this.validationRules.email && valueToCheck) {
      this.emailValidator.value = valueToCheck;
      if (!this.emailValidator.checkValidity()) {
        this.validationError =
          this.validationMessages.email || 'Введите корректный email';
        return false;
      }
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

    // Pattern
    if (this.validationRules.pattern && valueToCheck) {
      if (!this.compiledPatterns.pattern.test(valueToCheck)) {
        this.validationError =
          this.validationMessages.pattern || 'Некорректный формат';
        return false;
      }
    }

    return true;
  }

  // ============================================================================
  // Обработчики событий
  // ============================================================================

  handleInput(event) {
    this.value = event.target.value;

    // Обновляем флаг заполненности маски
    if (this.hasMask && this.maskInstance) {
      this.isMaskFilled = this.maskInstance.unmaskedValue.length > 0;
    }

    // Валидация в режиме onblur (если поле уже было тронуто)
    if (this.touched && this.validationMode === 'onblur') {
      this.validate();
    }

    // Ограничение длины для number
    if (this.type === 'number' && this.maxLengthAttr) {
      const digits = this.value.replace(/[^0-9]/g, '');
      if (digits.length > this.maxLengthAttr) {
        this.value = this.value.slice(0, -1);
        this.$refs.input.value = this.value;
      }
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
      this.$refs.input?.focus();
    }
  }

  // ============================================================================
  // UI действия
  // ============================================================================

  togglePasswordVisibility() {
    this.showPassword = !this.showPassword;
    this.$nextTick(() => this.$refs.input.focus());
  }

  clear() {
    this.value = '';
    this.validationError = '';
    this.isMaskFilled = false;

    // Диспатчим нативное input событие для x-model
    this.$nextTick(() => {
      if (this.$refs.input) {
        this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
        this.$refs.input.focus();
      }
    });

    this.$dispatch('input-cleared');
  }

  // ============================================================================
  // Утилиты
  // ============================================================================

  /**
   * Выполняет Alpine-выражение в контексте компонента.
   * Используется для onInput/onBlur callbacks.
   */
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

/** Регистрация компонента input в Alpine */
export function registerInput() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine не загружен, компонент Input не зарегистрирован');
    return;
  }
  window.Alpine.data('input', (props) => new InputComponent(props));
  console.log('✅ Input компонент зарегистрирован');
}

// Авто-регистрация
if (typeof window.Alpine !== 'undefined') {
  registerInput();
} else {
  document.addEventListener('alpine:init', () => registerInput());
}

export default InputComponent;