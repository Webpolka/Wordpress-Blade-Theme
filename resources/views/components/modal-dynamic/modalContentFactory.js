/**
 * ModalContentFactory
 * Singleton - не загружается повторно
 */

// Проверяем существует ли уже

class ModalContentFactory {
  static profileForm(user = { name: 'Иван', email: 'ivan@mail.ru' }) {
    return `
        <form id="profile-form" class="space-y-4" onsubmit="event.preventDefault(); alert('Сохранено!'); window.modalManager.closeDynamic();">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Имя</label>
            <input type="text" name="name" value="${user.name}" 
                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input type="email" name="email" value="${user.email}" 
                   class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100">
          </div>
          <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-modal-close class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
              Отмена
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm transition-colors">
              Сохранить
            </button>
          </div>
        </form>
      `;
  }

  static confirm({
    message = 'Вы уверены?',
    confirmText = 'OK',
    cancelText = 'Отмена',
    onConfirm = null,
    variant = 'primary',
  } = {}) {
    const variantClasses = {
      primary: 'bg-blue-500 hover:bg-blue-600 text-white',
      destructive: 'bg-red-500 hover:bg-red-600 text-white',
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
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">${message}</p>
        <div class="flex justify-end gap-2">
          <button data-modal-close class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            ${cancelText}
          </button>
          <button ${confirmAction} class="px-4 py-2 ${buttonClass} rounded-md text-sm transition-colors">
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
      variantClasses[variant] || 'text-gray-600 dark:text-gray-400';

    return `<p class="text-sm ${textClass}">${text}</p>`;
  }

  static html(html) {
    return html;
  }
}

export default ModalContentFactory;
