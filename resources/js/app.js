// resources/js/app.js
import Alpine from 'alpinejs';
import IMask from 'imask';
import { BaseHelpers } from "./base-helpers";
import { loadComponentScripts } from './components.js';
import initAutoRem from './autorem';
import { initViewport } from "./viewport";

BaseHelpers.addLoadedClass();
BaseHelpers.calcScrollbarWidth();
BaseHelpers.addTouchClass();

initAutoRem({
  baseSiteWidth: 1440,
  baseFontSize: 16
});

initViewport({
  breakpoint: 1440,
  designWidth: 1440
});

// Делаем Alpine глобальным
window.Alpine = Alpine;
window.IMask = IMask;

// Загружаем скрипты компонентов и ждём их завершения и стартуем Alpine после загрузки всех скриптов
loadComponentScripts().then(() => {
  Alpine.start();
});
