// resources/js/app.js
import Alpine from 'alpinejs';
import IMask from 'imask';

import { loadComponentScripts } from './components.js';

// Делаем Alpine глобальным
window.Alpine = Alpine;
window.IMask = IMask;

// Загружаем скрипты компонентов и ждём их завершения и стартуем Alpine после загрузки всех скриптов
loadComponentScripts().then(() => {
  Alpine.start();
});
