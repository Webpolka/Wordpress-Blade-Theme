/**
 * Tooltip Component for Alpine.js
 *
 * Pop-up hint with support for:
 * - 4 positions (top, bottom, left, right)
 * - Appearance/disappearance delay
 * - Arrow (optional)
 * - Themes (dark, light) and custom colors
 * - Automatic average color calculation for gradients
 * - Accessibility (ARIA attributes)
 * - Custom content
 */
class TooltipComponent {
  constructor(props) {
    this.position = props.position ?? 'top';
    this.delay = props.delay ?? 200;
    this.arrow = props.arrow ?? true;
    this.theme = props.theme ?? 'dark';
    this.color = props.color ?? null;
    this.distance = props.distance ?? 0.5;  // ← distance in rem (default 8)
    this.id = props.id ?? `tooltip-${Math.random().toString(36).substr(2, 9)}`;

    this.isOpen = false;
    this.showTimeout = null;
    this.hideTimeout = null;
  }

  init() {
    if (typeof this.$cleanup === 'function') {
      this.$cleanup(() => this.destroy());
    } else {
      this.$el.addEventListener('alpine:destroy', () => this.destroy());
    }
  }

  destroy() {
    clearTimeout(this.showTimeout);
    clearTimeout(this.hideTimeout);
  }

  show() {
    clearTimeout(this.hideTimeout);

    if (this.delay > 0) {
      this.showTimeout = setTimeout(() => {
        this.isOpen = true;
      }, this.delay);
    } else {
      this.isOpen = true;
    }
  }

  hide() {
    clearTimeout(this.showTimeout);
    this.hideTimeout = setTimeout(() => {
      this.isOpen = false;
    }, 100);
  }

  get positionClasses() {
    const positions = {
      top: 'bottom-full left-1/2 -translate-x-1/2',
      bottom: 'top-full left-1/2 -translate-x-1/2',
      left: 'right-full top-1/2 -translate-y-1/2',
      right: 'left-full top-1/2 -translate-y-1/2',
    };
    return positions[this.position] || positions.top;
  }

  get distanceStyle() {
    const d = `${this.distance}rem`;
    const distances = {
      top: `margin-bottom: ${d}`,
      bottom: `margin-top: ${d}`,
      left: `margin-right: ${d}`,
      right: `margin-left: ${d}`,
    };
    return distances[this.position] || distances.top;
  }

  get arrowClasses() {
    const arrows = {
      top: 'bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 rotate-45',
      bottom: 'top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 rotate-45',
      left: 'right-0 top-1/2 -translate-y-1/2 translate-x-1/2 rotate-45',
      right: 'left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 rotate-45',
    };
    return arrows[this.position] || arrows.top;
  }

  get arrowColorClasses() {
    if (this.color) {
      const isGradient = /gradient/.test(this.color);
      
      if (!isGradient) {
        const bgMatch = this.color.match(/bg-(\w+)-(\d+)/);
        if (bgMatch) {
          return `bg-${bgMatch[1]}-${bgMatch[2]}`;
        }
        
        const bgSimpleMatch = this.color.match(/bg-(\w+)(?:\s|$)/);
        if (bgSimpleMatch) {
          return `bg-${bgSimpleMatch[1]}`;
        }
      }
      
      return '';
    }

    // Design System: Привязка к семантическим переменным
    if (this.theme === 'light') {
      return 'bg-popover border-t border-l border-border';
    }

    return 'bg-foreground';
  }

  get arrowBgStyle() {
    if (!this.color) return '';
    
    const isGradient = /gradient/.test(this.color);
    if (!isGradient) return '';
    
    const fromMatch = this.color.match(/from-(\w+)-(\d+)/);
    const toMatch = this.color.match(/to-(\w+)-(\d+)/);
    
    if (fromMatch && toMatch) {
      const fromHex = TooltipComponent.COLOR_MAP[`${fromMatch[1]}-${fromMatch[2]}`];
      const toHex = TooltipComponent.COLOR_MAP[`${toMatch[1]}-${toMatch[2]}`];
      
      if (fromHex && toHex) {
        const midHex = TooltipComponent.mixColors(fromHex, toHex, 0.5);
        return `background: ${midHex}`;
      }
    }
    
    if (fromMatch) {
      const fromHex = TooltipComponent.COLOR_MAP[`${fromMatch[1]}-${fromMatch[2]}`];
      if (fromHex) return `background: ${fromHex}`;
    }
    
    if (toMatch) {
      const toHex = TooltipComponent.COLOR_MAP[`${toMatch[1]}-${toMatch[2]}`];
      if (toHex) return `background: ${toHex}`;
    }
    
    return '';
  }

  get themeClasses() {
    if (this.color) {
      return this.color;
    }

    // Design System: Инверсия цветов для темной темы, поповер для светлой
    const themes = {
      dark: 'bg-foreground text-background',
      light: 'bg-popover text-popover-foreground border border-border shadow-lg',
    };
    return themes[this.theme] || themes.dark;
  }

  static COLOR_MAP = {
    'slate-50': '#f8fafc', 'slate-100': '#f1f5f9', 'slate-200': '#e2e8f0',
    'slate-300': '#cbd5e1', 'slate-400': '#94a3b8', 'slate-500': '#64748b',
    'slate-600': '#475569', 'slate-700': '#334155', 'slate-800': '#1e293b',
    'slate-900': '#0f172a', 'slate-950': '#020617',
    
    'gray-50': '#f9fafb', 'gray-100': '#f3f4f6', 'gray-200': '#e5e7eb',
    'gray-300': '#d1d5db', 'gray-400': '#9ca3af', 'gray-500': '#6b7280',
    'gray-600': '#4b5563', 'gray-700': '#374151', 'gray-800': '#1f2937',
    'gray-900': '#111827', 'gray-950': '#030712',
    
    'red-50': '#fef2f2', 'red-100': '#fee2e2', 'red-200': '#fecaca',
    'red-300': '#fca5a5', 'red-400': '#f87171', 'red-500': '#ef4444',
    'red-600': '#dc2626', 'red-700': '#b91c1c', 'red-800': '#991b1b',
    'red-900': '#7f1d1d', 'red-950': '#450a0a',
    
    'orange-50': '#fff7ed', 'orange-100': '#ffedd5', 'orange-200': '#fed7aa',
    'orange-300': '#fdba74', 'orange-400': '#fb923c', 'orange-500': '#f97316',
    'orange-600': '#ea580c', 'orange-700': '#c2410c', 'orange-800': '#9a3412',
    'orange-900': '#7c2d12', 'orange-950': '#431407',
    
    'amber-50': '#fffbeb', 'amber-100': '#fef3c7', 'amber-200': '#fde68a',
    'amber-300': '#fcd34d', 'amber-400': '#fbbf24', 'amber-500': '#f59e0b',
    'amber-600': '#d97706', 'amber-700': '#b45309', 'amber-800': '#92400e',
    'amber-900': '#78350f', 'amber-950': '#451a03',
    
    'yellow-50': '#fefce8', 'yellow-100': '#fef9c3', 'yellow-200': '#fef08a',
    'yellow-300': '#fde047', 'yellow-400': '#facc15', 'yellow-500': '#eab308',
    'yellow-600': '#ca8a04', 'yellow-700': '#a16207', 'yellow-800': '#854d0e',
    'yellow-900': '#713f12', 'yellow-950': '#422006',
    
    'lime-50': '#f7fee7', 'lime-100': '#ecfccb', 'lime-200': '#d9f99d',
    'lime-300': '#bef264', 'lime-400': '#a3e635', 'lime-500': '#84cc16',
    'lime-600': '#65a30d', 'lime-700': '#4d7c0f', 'lime-800': '#3f6212',
    'lime-900': '#365314', 'lime-950': '#1a2e05',
    
    'green-50': '#f0fdf4', 'green-100': '#dcfce7', 'green-200': '#bbf7d0',
    'green-300': '#86efac', 'green-400': '#4ade80', 'green-500': '#22c55e',
    'green-600': '#16a34a', 'green-700': '#15803d', 'green-800': '#166534',
    'green-900': '#14532d', 'green-950': '#052e16',
    
    'emerald-50': '#ecfdf5', 'emerald-100': '#d1fae5', 'emerald-200': '#a7f3d0',
    'emerald-300': '#6ee7b7', 'emerald-400': '#34d399', 'emerald-500': '#10b981',
    'emerald-600': '#059669', 'emerald-700': '#047857', 'emerald-800': '#065f46',
    'emerald-900': '#064e3b', 'emerald-950': '#022c22',
    
    'teal-50': '#f0fdfa', 'teal-100': '#ccfbf1', 'teal-200': '#99f6e4',
    'teal-300': '#5eead4', 'teal-400': '#2dd4bf', 'teal-500': '#14b8a6',
    'teal-600': '#0d9488', 'teal-700': '#0f766e', 'teal-800': '#115e59',
    'teal-900': '#134e4a', 'teal-950': '#042f2e',
    
    'cyan-50': '#ecfeff', 'cyan-100': '#cffafe', 'cyan-200': '#a5f3fc',
    'cyan-300': '#67e8f9', 'cyan-400': '#22d3ee', 'cyan-500': '#06b6d4',
    'cyan-600': '#0891b2', 'cyan-700': '#0e7490', 'cyan-800': '#155e75',
    'cyan-900': '#164e63', 'cyan-950': '#083344',
    
    'sky-50': '#f0f9ff', 'sky-100': '#e0f2fe', 'sky-200': '#bae6fd',
    'sky-300': '#7dd3fc', 'sky-400': '#38bdf8', 'sky-500': '#0ea5e9',
    'sky-600': '#0284c7', 'sky-700': '#0369a1', 'sky-800': '#075985',
    'sky-900': '#0c4a6e', 'sky-950': '#082f49',
    
    'blue-50': '#eff6ff', 'blue-100': '#dbeafe', 'blue-200': '#bfdbfe',
    'blue-300': '#93c5fd', 'blue-400': '#60a5fa', 'blue-500': '#3b82f6',
    'blue-600': '#2563eb', 'blue-700': '#1d4ed8', 'blue-800': '#1e40af',
    'blue-900': '#1e3a8a', 'blue-950': '#172554',
    
    'indigo-50': '#eef2ff', 'indigo-100': '#e0e7ff', 'indigo-200': '#c7d2fe',
    'indigo-300': '#a5b4fc', 'indigo-400': '#818cf8', 'indigo-500': '#6366f1',
    'indigo-600': '#4f46e5', 'indigo-700': '#4338ca', 'indigo-800': '#3730a3',
    'indigo-900': '#312e81', 'indigo-950': '#1e1b4b',
    
    'violet-50': '#f5f3ff', 'violet-100': '#ede9fe', 'violet-200': '#ddd6fe',
    'violet-300': '#c4b5fd', 'violet-400': '#a78bfa', 'violet-500': '#8b5cf6',
    'violet-600': '#7c3aed', 'violet-700': '#6d28d9', 'violet-800': '#5b21b6',
    'violet-900': '#4c1d95', 'violet-950': '#2e1065',
    
    'purple-50': '#faf5ff', 'purple-100': '#f3e8ff', 'purple-200': '#e9d5ff',
    'purple-300': '#d8b4fe', 'purple-400': '#c084fc', 'purple-500': '#a855f7',
    'purple-600': '#9333ea', 'purple-700': '#7e22ce', 'purple-800': '#6b21a8',
    'purple-900': '#581c87', 'purple-950': '#3b0764',
    
    'fuchsia-50': '#fdf4ff', 'fuchsia-100': '#fae8ff', 'fuchsia-200': '#f5d0fe',
    'fuchsia-300': '#f0abfc', 'fuchsia-400': '#e879f9', 'fuchsia-500': '#d946ef',
    'fuchsia-600': '#c026d3', 'fuchsia-700': '#a21caf', 'fuchsia-800': '#86198f',
    'fuchsia-900': '#701a75', 'fuchsia-950': '#4a044e',
    
    'pink-50': '#fdf2f8', 'pink-100': '#fce7f3', 'pink-200': '#fbcfe8',
    'pink-300': '#f9a8d4', 'pink-400': '#f472b6', 'pink-500': '#ec4899',
    'pink-600': '#db2777', 'pink-700': '#be185d', 'pink-800': '#9d174d',
    'pink-900': '#831843', 'pink-950': '#500724',
    
    'rose-50': '#fff1f2', 'rose-100': '#ffe4e6', 'rose-200': '#fecdd3',
    'rose-300': '#fda4af', 'rose-400': '#fb7185', 'rose-500': '#f43f5e',
    'rose-600': '#e11d48', 'rose-700': '#be123c', 'rose-800': '#9f1239',
    'rose-900': '#881337', 'rose-950': '#4c0519',
    
    'black': '#000000',
    'white': '#ffffff',
    'transparent': 'transparent',
  };

  static mixColors(color1, color2, ratio = 0.5) {
    const hex = (c) => parseInt(c, 16);
    
    const r1 = hex(color1.slice(1, 3));
    const g1 = hex(color1.slice(3, 5));
    const b1 = hex(color1.slice(5, 7));
    
    const r2 = hex(color2.slice(1, 3));
    const g2 = hex(color2.slice(3, 5));
    const b2 = hex(color2.slice(5, 7));
    
    const r = Math.round(r1 + (r2 - r1) * ratio);
    const g = Math.round(g1 + (g2 - g1) * ratio);
    const b = Math.round(b1 + (b2 - b1) * ratio);
    
    const toHex = (n) => n.toString(16).padStart(2, '0');
    return `#${toHex(r)}${toHex(g)}${toHex(b)}`;
  }
}

export function registerTooltip() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine is not loaded, Tooltip component not registered');
    return;
  }
  window.Alpine.data('tooltip', (props) => new TooltipComponent(props));
  console.log('✅ Tooltip component registered');
}

//  Жестко вешаем слушатель на alpine:init
document.addEventListener('alpine:init', () => {
  registerTooltip();
});

export default TooltipComponent;