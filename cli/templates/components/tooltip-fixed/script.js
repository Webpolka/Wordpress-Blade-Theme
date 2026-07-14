/**
 * Tooltip Fixed
 * 
 * Fixed-position tooltip, not clipped in overflow containers.
 * - Show on hover/focus
 * - Hides on scroll, reappears when scrolling stops
 * - All sizes in rem
 * - One tooltip per page (Singleton)
 * - Average color calculation for gradients
 * - Trigger visibility check (doesn't show if trigger is off-screen)
 */
class TooltipFixedManager {
  constructor() {
    if (TooltipFixedManager.instance) return TooltipFixedManager.instance;

    this.tooltip = null;
    this.currentTrigger = null;
    this.showTimer = null;
    this.hideTimer = null;
    this.scrollTimeout = null;
    this.lastMousePos = { x: 0, y: 0 };
    
    this.REM = {
      distance: 0.5,
      arrow: 0.5,
      padding: 0.5,
    };
    
    this.init();
    TooltipFixedManager.instance = this;
  }

  // ============================================================================
  // Утилиты
  // ============================================================================

  remToPx(rem) {
    const fontSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
    return rem * fontSize;
  }

  /**
   * Checks element visibility in viewport
   */
  isElementVisible(el, offsetInRem = 3.5) {
    const rect = el.getBoundingClientRect();
    const remInPx = parseFloat(getComputedStyle(document.documentElement).fontSize);
    const offsetInPx = offsetInRem * remInPx;
    
    return (
      rect.bottom > offsetInPx &&
      rect.top < window.innerHeight - offsetInPx &&
      rect.right > 0 &&
      rect.left < window.innerWidth
    );
  }

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

  static COLOR_MAP = {
    'slate-50': '#f8fafc', 'slate-100': '#f1f5f9', 'slate-200': '#e2e8f0',
    'slate-300': '#cbd5e1', 'slate-400': '#94a3b8', 'slate-500': '#64748b',
    'slate-600': '#475569', 'slate-700': '#334155', 'slate-800': '#1e293b',
    'slate-900': '#0f172a', 'slate-950': '#020617',
    'gray-50': '#f9fafb', 'gray-100': '#f3f4f6', 'gray-200': '#e5e7eb',
    'gray-300': '#d1d5db', 'gray-400': '#9ca3af', 'gray-500': '#6b7280',
    'gray-600': '#4b5563', 'gray-700': '#374151', 'gray-800': '#1f2937',
    'gray-900': '#111827', 'gray-950': '#030712',
    'zinc-50': '#fafafa', 'zinc-100': '#f4f4f5', 'zinc-200': '#e4e4e7',
    'zinc-300': '#d4d4d8', 'zinc-400': '#a1a1aa', 'zinc-500': '#71717a',
    'zinc-600': '#52525b', 'zinc-700': '#3f3f46', 'zinc-800': '#27272a',
    'zinc-900': '#18181b', 'zinc-950': '#09090b',
    'neutral-50': '#fafafa', 'neutral-100': '#f5f5f5', 'neutral-200': '#e5e5e5',
    'neutral-300': '#d4d4d4', 'neutral-400': '#a3a3a3', 'neutral-500': '#737373',
    'neutral-600': '#525252', 'neutral-700': '#404040', 'neutral-800': '#262626',
    'neutral-900': '#171717', 'neutral-950': '#0a0a0a',
    'stone-50': '#fafaf9', 'stone-100': '#f5f5f4', 'stone-200': '#e7e5e4',
    'stone-300': '#d6d3d1', 'stone-400': '#a8a29e', 'stone-500': '#78716c',
    'stone-600': '#57534e', 'stone-700': '#44403c', 'stone-800': '#292524',
    'stone-900': '#1c1917', 'stone-950': '#0c0a09',
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
  };

  // ============================================================================
  // Инициализация
  // ============================================================================

  init() {
    document.addEventListener('mouseenter', (e) => {
      const trigger = e.target.closest?.('[data-tooltip]');
      if (trigger) this.show(trigger);
    }, true);

    document.addEventListener('mouseleave', (e) => {
      const trigger = e.target.closest?.('[data-tooltip]');
      if (trigger) this.hide(trigger);
    }, true);

    document.addEventListener('focusin', (e) => {
      const trigger = e.target.closest?.('[data-tooltip]');
      if (trigger) this.show(trigger);
    });

    document.addEventListener('focusout', (e) => {
      const trigger = e.target.closest?.('[data-tooltip]');
      if (trigger) this.hide(trigger);
    });

    document.addEventListener('mousemove', (e) => {
      this.lastMousePos = { x: e.clientX, y: e.clientY };
    }, { passive: true });

    document.addEventListener('scroll', () => {
      if (this.tooltip) {
        this.hideCurrent();
      }
      
      clearTimeout(this.scrollTimeout);
      this.scrollTimeout = setTimeout(() => {
        this.restoreIfHovering();
      }, 100);
    }, { passive: true, capture: true });

    window.addEventListener('resize', () => {
      if (this.tooltip && this.currentTrigger?.isConnected) {
        if (!this.isElementVisible(this.currentTrigger)) {
          this.removeTooltip();
          return;
        }
        this.updatePosition();
      }
    }, { passive: true });
  }

  // ============================================================================
  // Публичные методы
  // ============================================================================

 show(trigger) {
    if (!trigger?.dataset?.tooltip) return;

    // Проверяем видимость триггера
    if (!this.isElementVisible(trigger)) return;

    clearTimeout(this.hideTimer);
    this.hideTimer = null;

    if (this.currentTrigger === trigger && this.tooltip) return;

    clearTimeout(this.showTimer);

    //  Строгая проверка delay, чтобы 0 тоже работал
    const delayAttr = trigger.dataset.tooltipDelay;
    const delay = (delayAttr !== undefined && !isNaN(parseInt(delayAttr))) 
      ? parseInt(delayAttr) 
      : 200;

    this.showTimer = setTimeout(() => {
      //  Повторная проверка перед созданием (мог успеть прокрутиться)
      if (!this.isElementVisible(trigger)) return;

      this.removeTooltip();

      this.tooltip = this.create(trigger);
      this.currentTrigger = trigger;
      this.updatePosition();

      requestAnimationFrame(() => {
        if (this.tooltip) {
          this.tooltip.style.opacity = '1';
        }
      });
    }, delay);
  }


  hide(trigger) {
    if (!trigger || this.currentTrigger !== trigger) return;

    clearTimeout(this.showTimer);
    this.showTimer = null;

    if (!this.tooltip) return;

    this.tooltip.style.opacity = '0';

    this.hideTimer = setTimeout(() => {
      this.removeTooltip();
    }, 150);
  }

  hideCurrent() {
    clearTimeout(this.showTimer);
    this.showTimer = null;

    if (!this.tooltip) return;
    
    this.removeTooltip();
  }

  // ============================================================================
  // Приватные методы
  // ============================================================================

  restoreIfHovering() {
    const el = document.elementFromPoint(this.lastMousePos.x, this.lastMousePos.y);
    if (!el) return;
    
    const trigger = el.closest?.('[data-tooltip]');
    
    if (trigger && this.isElementVisible(trigger)) {
      if (this.currentTrigger === trigger && this.tooltip) {
        this.tooltip.style.opacity = '1';
        this.updatePosition();
      } else if (this.currentTrigger !== trigger) {
        this.removeTooltip();
        this.show(trigger);
      }
    } else {
      this.removeTooltip();
    }
  }

  removeTooltip() {
    clearTimeout(this.hideTimer);
    this.hideTimer = null;

    if (this.tooltip) {
      this.tooltip.remove();
      this.tooltip = null;
    }
    this.currentTrigger = null;
  }

  create(trigger) {
    const text = trigger.dataset.tooltip;
    const position = trigger.dataset.tooltipPosition || 'top';
    const theme = trigger.dataset.tooltipTheme || 'dark';
    const color = trigger.dataset.tooltipColor || null;
    
    // МАГИЯ FIX: Строгая проверка distance, чтобы 0 тоже работал
    const distanceAttr = trigger.dataset.tooltipDistance;
    const distanceRem = (distanceAttr !== undefined && !isNaN(parseFloat(distanceAttr))) 
      ? parseFloat(distanceAttr) 
      : this.REM.distance;
      
    const arrow = trigger.dataset.tooltipArrow !== 'false';

    // НОВОЕ: Заменили gray на slate
    let themeClass = 'bg-slate-800 text-white';
    if (color) {
      themeClass = color;
    } else if (theme === 'light') {
      themeClass = 'bg-white text-slate-800 border border-slate-200 shadow-lg';
    }

    let arrowClass = '';
    let arrowBgColor = '';

    if (color) {
      const fromMatch = color.match(/from-(\w+)-(\d+)/);
      const toMatch = color.match(/to-(\w+)-(\d+)/);
      const bgMatch = color.match(/bg-(\w+)-(\d+)/);

      if (fromMatch && toMatch) {
        const fromHex = TooltipFixedManager.COLOR_MAP[`${fromMatch[1]}-${fromMatch[2]}`];
        const toHex = TooltipFixedManager.COLOR_MAP[`${toMatch[1]}-${toMatch[2]}`];
        
        if (fromHex && toHex) {
          arrowBgColor = TooltipFixedManager.mixColors(fromHex, toHex, 0.5);
        } else {
          arrowClass = 'bg-slate-800';
        }
      } else if (fromMatch) {
        arrowClass = `bg-${fromMatch[1]}-${fromMatch[2]}`;
      } else if (toMatch) {
        arrowClass = `bg-${toMatch[1]}-${toMatch[2]}`;
      } else if (bgMatch) {
        arrowClass = `bg-${bgMatch[1]}-${bgMatch[2]}`;
      } else {
        arrowClass = 'bg-slate-800';
      }
    } else if (theme === 'light') {
      arrowClass = 'bg-white border-t border-l border-slate-200';
    } else {
      arrowClass = 'bg-slate-800';
    }

    const el = document.createElement('div');
    el.className = `fixed z-[9999] pointer-events-none text-xs font-medium rounded-md shadow-sm whitespace-nowrap px-3 py-1.5 ${themeClass}`;
    el.setAttribute('role', 'tooltip');
    el.style.cssText = 'opacity: 0; transition: opacity 150ms ease;';
    el.textContent = text;

    if (arrow) {
      const arrowEl = document.createElement('div');
      const positions = {
        top: 'bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2',
        bottom: 'top-0 left-1/2 -translate-x-1/2 -translate-y-1/2',
        left: 'right-0 top-1/2 -translate-y-1/2 translate-x-1/2',
        right: 'left-0 top-1/2 -translate-y-1/2 -translate-x-1/2',
      };
      arrowEl.className = `absolute w-2 h-2 rotate-45 ${arrowClass} ${positions[position]}`;
      
      if (arrowBgColor) {
        arrowEl.style.background = arrowBgColor;
      }
      
      el.appendChild(arrowEl);
    }

    document.body.appendChild(el);
    el._trigger = trigger;
    el._distance = distanceRem;
    el._position = position;

    return el;
  }

  updatePosition() {
    if (!this.tooltip || !this.currentTrigger?.isConnected) return;

    const trigger = this.currentTrigger;
    const tooltip = this.tooltip;
    const tRect = trigger.getBoundingClientRect();
    const ttRect = tooltip.getBoundingClientRect();

    if (ttRect.width === 0 || ttRect.height === 0) {
      requestAnimationFrame(() => this.updatePosition());
      return;
    }

    const vw = window.innerWidth;
    const vh = window.innerHeight;
    
    const distancePx = this.remToPx(tooltip._distance);
    const arrowPx = this.remToPx(this.REM.arrow);
    const paddingPx = this.remToPx(this.REM.padding);
    
    const position = tooltip._position;

    let x = tRect.left + tRect.width / 2;
    let y = 0;

    switch (position) {
      case 'top':
        y = tRect.top - ttRect.height - distancePx - arrowPx;
        break;
      case 'bottom':
        y = tRect.bottom + distancePx + arrowPx;
        break;
      case 'left':
        x = tRect.left - ttRect.width - distancePx - arrowPx;
        y = tRect.top + tRect.height / 2;
        break;
      case 'right':
        x = tRect.right + distancePx + arrowPx;
        y = tRect.top + tRect.height / 2;
        break;
    }

    if (position === 'top' || position === 'bottom') {
      x = Math.max(paddingPx, Math.min(x, vw - ttRect.width - paddingPx));
      if (y < paddingPx) y = paddingPx;
      if (y + ttRect.height > vh - paddingPx) y = vh - ttRect.height - paddingPx;
    } else {
      y = Math.max(paddingPx, Math.min(y, vh - ttRect.height - paddingPx));
      if (x < paddingPx) x = paddingPx;
      if (x + ttRect.width > vw - paddingPx) x = vw - ttRect.width - paddingPx;
    }

    tooltip.style.left = `${Math.round(x)}px`;
    tooltip.style.top = `${Math.round(y)}px`;
    tooltip.style.transform = (position === 'top' || position === 'bottom')
      ? 'translateX(-50%)'
      : 'translateY(-50%)';
  }
}

// МАГИЯ FIX: Безопасная инициализация Singleton
if (typeof window !== 'undefined') {
  if (!window.tooltipFixedManager) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => {
        if (!window.tooltipFixedManager) {
          window.tooltipFixedManager = new TooltipFixedManager();
        }
      });
    } else {
      window.tooltipFixedManager = new TooltipFixedManager();
    }
  }
}

export default TooltipFixedManager;