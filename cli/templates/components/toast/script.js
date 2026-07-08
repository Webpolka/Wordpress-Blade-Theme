/**
 * resources/views/components/toast/script.js
 *
 * Скрипт компонента Toast.
 * Регистрирует стор `toast` в Alpine.js для управления всплывающими уведомлениями.
 *
 * Стор `$store.toast`:
 *   - messages: array – массив уведомлений [{ id, message, type, title }]
 *
 * Методы:
 *   - show({ message, type = 'default', title = '', timeout = 4000 }) – добавляет уведомление.
 *   - remove(id) – удаляет уведомление по ID.
 *
 * Пример использования:
 *   $store.toast.show({
 *       message: 'Успешно сохранено!',
 *       type: 'success',
 *       title: 'Готово',
 *       timeout: 3000
 *   });
 */

(function initToast() {
  // 1. Проверяем, что Alpine доступен глобально
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine не найден, пропускаем регистрацию стора Toast.');
    return;
  }

  // 2. Проверяем, не зарегистрирован ли уже стор 'toast'
  if (window.Alpine.store('toast')) {
    console.log('ℹ️ Стор Toast уже зарегистрирован, пропускаем.');
    return;
  }

  // 3. Регистрируем стор
  window.Alpine.store('toast', {
    messages: [],

    /**
     * Показать уведомление.
     * @param {Object} params
     * @param {string} params.message - Текст уведомления (обязательно).
     * @param {string} params.type - Тип: 'default', 'success', 'error', 'warning' (по умолчанию 'default').
     * @param {string} params.title - Заголовок (опционально).
     * @param {number} params.timeout - Время автоскрытия в мс (по умолчанию 4000). 0 – не скрывать.
     */
    show({ message, type = 'default', title = '', timeout = 4000 }) {
      if (!message) {
        console.warn('Toast: message обязателен');
        return;
      }
      const id = Date.now() + Math.random();
      this.messages.push({ id, message, type, title });

      if (timeout > 0) {
        setTimeout(() => {
          this.remove(id);
        }, timeout);
      }
    },

    /**
     * Удалить уведомление по ID.
     * @param {number|string} id
     */
    remove(id) {
      this.messages = this.messages.filter((m) => m.id !== id);
    },
  });

  console.log('✅ Стор Toast зарегистрирован');
})();
