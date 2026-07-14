/**
 * ModalContentFactory
 * Singleton - not loaded repeatedly
 */

class ModalContentFactory {
  static profileForm(user = { name: 'John', email: 'john@example.com' }) {
    return `
        <form id="profile-form" class="space-y-4" onsubmit="event.preventDefault(); alert('Saved!'); window.modalManager.closeDynamic();">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Name</label>
            <input type="text" name="name" value="${user.name}" 
                   class="w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-800 dark:bg-slate-800 dark:text-slate-100">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
            <input type="email" name="email" value="${user.email}" 
                   class="w-full border border-slate-300 dark:border-slate-600 rounded-md px-3 py-2 text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-800 dark:bg-slate-800 dark:text-slate-100">
          </div>
          <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-modal-close class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-800">
              Cancel
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-800">
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
      primary: 'bg-blue-600 hover:bg-blue-700 text-white',
      destructive: 'bg-red-600 hover:bg-red-700 text-white',
      secondary: 'bg-orange-500 hover:bg-orange-600 text-white',
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
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">${message}</p>
        <div class="flex justify-end gap-2">
          <button data-modal-close class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-800">
            ${cancelText}
          </button>
          <button ${confirmAction} class="px-4 py-2 ${buttonClass} rounded-md text-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:ring-offset-slate-800">
            ${confirmText}
          </button>
        </div>
      `;
  }

  static message({ text = '', variant = 'info' } = {}) {
    const variantClasses = {
      info: 'text-blue-600 dark:text-blue-400',
      success: 'text-green-600 dark:text-green-400',
      warning: 'text-yellow-600 dark:text-yellow-400',
      error: 'text-red-600 dark:text-red-400',
    };
    const textClass =
      variantClasses[variant] || 'text-slate-600 dark:text-slate-400';

    return `<p class="text-sm ${textClass}">${text}</p>`;
  }

  static html(html) {
    return html;
  }
}

export default ModalContentFactory;