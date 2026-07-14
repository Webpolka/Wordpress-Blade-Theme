/**
 * ModalContentFactory
 * Singleton - not loaded repeatedly
 */

class ModalContentFactory {
  static profileForm(user = { name: 'John', email: 'john@example.com' }) {
    return `
        <form id="profile-form" class="space-y-4" onsubmit="event.preventDefault(); alert('Saved!'); window.modalManager.closeDynamic();">
          <div>
            <label class="block text-sm font-medium text-foreground mb-1">Name</label>
            <input type="text" name="name" value="${user.name}" 
                   class="w-full border border-input rounded-md px-3 py-2 text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background bg-background text-foreground">
          </div>
          <div>
            <label class="block text-sm font-medium text-foreground mb-1">Email</label>
            <input type="email" name="email" value="${user.email}" 
                   class="w-full border border-input rounded-md px-3 py-2 text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background bg-background text-foreground">
          </div>
          <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-modal-close class="px-4 py-2 border border-input rounded-md text-sm hover:bg-accent hover:text-accent-foreground transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background">
              Cancel
            </button>
            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-md text-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background">
              Save
            </button>
          </div>
        </form>
      `;
  }

  static confirm({
    message = 'Are you sure?',
    confirmText = 'OK',
    cancelText = 'Cancel',
    onConfirm = null,
    variant = 'primary',
  } = {}) {
    const variantClasses = {
      primary: 'bg-primary hover:bg-primary/90 text-primary-foreground',
      destructive: 'bg-destructive hover:bg-destructive/90 text-destructive-foreground',
      secondary: 'bg-secondary hover:bg-secondary/80 text-secondary-foreground',
    };
    const buttonClass = variantClasses[variant] || variantClasses['primary'];

    let confirmAction;
    if (onConfirm) {
      const callbackStr = onConfirm.toString();
      confirmAction = `onclick="(${callbackStr})(); window.modalManager.closeDynamic();"`;
    } else {
      confirmAction = 'data-modal-close';
    }

    return `
        <p class="text-sm text-muted-foreground mb-4">${message}</p>
        <div class="flex justify-end gap-2">
          <button data-modal-close class="px-4 py-2 border border-input rounded-md text-sm hover:bg-accent hover:text-accent-foreground transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background">
            ${cancelText}
          </button>
          <button ${confirmAction} class="px-4 py-2 ${buttonClass} rounded-md text-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background">
            ${confirmText}
          </button>
        </div>
      `;
  }

  static message({ text = '', variant = 'info' } = {}) {
    const variantClasses = {
      info: 'text-primary',
      success: 'text-green-600 dark:text-green-400',
      warning: 'text-yellow-600 dark:text-yellow-400',
      error: 'text-destructive',
    };
    const textClass =
      variantClasses[variant] || 'text-muted-foreground';

    return `<p class="text-sm ${textClass}">${text}</p>`;
  }

  static html(html) {
    return html;
  }
}

export default ModalContentFactory;