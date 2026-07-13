/**
 * Input Component for Alpine.js
 * 
 * Universal input field with support for:
 * - Validation (required, email, minlength, maxlength, pattern)
 * - Masks (IMask)
 * - Password visibility toggle
 * - Clear input
 * - Custom callbacks (onInput, onBlur)
 * - Accessibility (ARIA attributes)
 */
class InputComponent {
  constructor(props) {
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
    
    // НОВОЕ: Переведенные строки для ARIA-атрибутов
    this.labelShowPassword = props.labelShowPassword || 'Show password';
    this.labelHidePassword = props.labelHidePassword || 'Hide password';

    this.compiledPatterns = {};
    if (props.validationRules?.pattern) {
      try {
        this.compiledPatterns.pattern = new RegExp(props.validationRules.pattern);
      } catch (e) {
        console.warn('Invalid regex pattern:', props.validationRules.pattern, e);
      }
    }

    this.emailValidator = document.createElement('input');
    this.emailValidator.type = 'email';

    this.validationError = '';
    this.touched = false;
    this.showPassword = false;
    this.isMaskFilled = false;

    this.maskInstance = null;
    this.validationTimeout = null;
  }

  init() {
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

    this.$nextTick(() => {
      this.initMask();
    });

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
    if (this.maskInstance) {
      this.maskInstance.destroy();
      this.maskInstance = null;
    }
  }

  initMask() {
    const input = this.$refs.input;
    if (!input) return;

    const mask = input.getAttribute('mask');
    if (!mask) return;

    if (!window.IMask) {
      console.warn('IMask is not loaded, input mask disabled.');
      return;
    }

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

  validate() {
    this.touched = true;
    this.validationError = '';

    let valueToCheck = this.value || '';
    if (this.maskInstance) {
      valueToCheck = this.maskInstance.unmaskedValue || '';
    } else if (this.type !== 'password') {
      valueToCheck = valueToCheck.trim();
    }

    if (this.validationRules.required && !valueToCheck) {
      this.validationError =
        this.validationMessages.required || 'This field is required';
      return false;
    }

    if (this.validationRules.email && valueToCheck) {
      this.emailValidator.value = valueToCheck;
      if (!this.emailValidator.checkValidity()) {
        this.validationError =
          this.validationMessages.email || 'Please enter a valid email address';
        return false;
      }
    }

    if (
      this.validationRules.minlength &&
      valueToCheck.length < this.validationRules.minlength
    ) {
      this.validationError =
        this.validationMessages.minlength ||
        `Minimum ${this.validationRules.minlength} characters`;
      return false;
    }

    if (
      this.validationRules.maxlength &&
      valueToCheck.length > this.validationRules.maxlength
    ) {
      this.validationError =
        this.validationMessages.maxlength ||
        `Maximum ${this.validationRules.maxlength} characters`;
      return false;
    }

    if (this.validationRules.pattern && valueToCheck) {
      if (!this.compiledPatterns.pattern.test(valueToCheck)) {
        this.validationError =
          this.validationMessages.pattern || 'Invalid format';
        return false;
      }
    }

    return true;
  }

  handleInput(event) {
    this.value = event.target.value;

    if (this.hasMask && this.maskInstance) {
      this.isMaskFilled = this.maskInstance.unmaskedValue.length > 0;
    }

    if (this.touched && this.validationMode === 'onblur') {
      this.validate();
    }

    if (this.type === 'number' && this.maxLengthAttr) {
      const digits = this.value.replace(/[^0-9]/g, '');
      if (digits.length > this.maxLengthAttr) {
        this.value = this.value.slice(0, -1);
        this.$refs.input.value = this.value;
      }
    }

    if (this.onInputCallback) {
      this.evaluateExpression(this.onInputCallback);
    }
  }

  handleBlur() {
    if (this.validationTimeout) {
      clearTimeout(this.validationTimeout);
    }

    if (this.validationMode === 'onblur' || this.validationMode === 'change') {
      this.validate();
    }

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

  togglePasswordVisibility() {
    this.showPassword = !this.showPassword;
    this.$nextTick(() => this.$refs.input.focus());
  }

  clear() {
    this.value = '';
    this.validationError = '';
    this.isMaskFilled = false;

    this.$nextTick(() => {
      if (this.$refs.input) {
        this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
        this.$refs.input.focus();
      }
    });

    this.$dispatch('input-cleared');
  }

  evaluateExpression(expr) {
    if (!expr || typeof expr !== 'string') return;
    try {
      const fn = new Function('with(this) { ' + expr + ' }');
      fn.call(this);
    } catch (e) {
      console.warn('Error executing callback:', e);
    }
  }
}

export function registerInput() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine is not loaded, Input component not registered');
    return;
  }
  window.Alpine.data('input', (props) => new InputComponent(props));
  console.log('✅ Input component registered');
}

// Жестко вешаем слушатель на alpine:init
document.addEventListener('alpine:init', () => {
  registerInput();
});

export default InputComponent;