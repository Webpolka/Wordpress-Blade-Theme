/**
 * resources/views/components/toast/script.js
 *
 * Toast Component Script.
 * Registers the `toast` store in Alpine.js to manage pop-up notifications.
 */

function registerToastStore() {
  if (typeof window.Alpine === 'undefined') {
    console.warn('⚠️ Alpine is not loaded, skipping Toast store registration.');
    return;
  }

  // Если стор уже есть, выходим
  if (window.Alpine.store('toast')) {
    console.log('ℹ️ Toast store already registered, skipping.');
    return;
  }

  // Регистрируем стор
  window.Alpine.store('toast', {
    messages: [],

    show({ message, type = 'default', title = '', timeout = 4000 }) {
      if (!message) {
        console.warn('Toast: message is required');
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

    remove(id) {
      this.messages = this.messages.filter((m) => m.id !== id);
    },
  });

  console.log('✅ Toast store registered');
}

// МАГИЯ FIX: Если Alpine уже загружен — регистрируем сразу. Если нет — ждем alpine:init.
if (typeof window.Alpine !== 'undefined') {
  registerToastStore();
} else {
  document.addEventListener('alpine:init', registerToastStore);
}