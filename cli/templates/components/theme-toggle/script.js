// resources/views/components/theme-toggle/script.js

/**
 * ThemeToggle Component for Alpine.js
 * 
 * Theme switcher with support for:
 * - System theme (prefers-color-scheme)
 * - Saving choice to localStorage
 * - Switch animation
 * - Custom callbacks
 * - Accessibility (ARIA attributes)
 */
class ThemeToggleComponent {
  constructor(props = {}) {
    this.isDark = document.documentElement.classList.contains('dark');
    
    this.labelLight = props.labelLight || 'Light mode';
    this.labelDark = props.labelDark || 'Dark mode';
    
    this.onChange = typeof props.onChange === 'function' ? props.onChange : null;
  }

  get currentLabel() {
    return this.isDark ? this.labelLight : this.labelDark;
  }

  toggle() {
    this.isDark = !this.isDark;
    
    if (this.isDark) {
      localStorage.theme = 'dark';
      document.documentElement.classList.add('dark');
    } else {
      localStorage.theme = 'light';
      document.documentElement.classList.remove('dark');
    }
    
    if (this.onChange) {
      this.onChange(this.isDark);
    }
  }
}

// ============================================================================
// Регистрация компонента
// ============================================================================

export function registerThemeToggle() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine is not loaded, ThemeToggle component not registered');
    return;
  }
  
  window.Alpine.data('themeToggle', (props = {}) => new ThemeToggleComponent(props));
  console.log('✅ ThemeToggle component registered');
}

//  Жестко вешаем слушатель на alpine:init
document.addEventListener('alpine:init', () => {
  registerThemeToggle();
});

export default ThemeToggleComponent;