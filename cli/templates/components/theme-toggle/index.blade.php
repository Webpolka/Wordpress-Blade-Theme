{{--
  ============================================================
  Компонент: Theme Toggle
  Описание: Переключатель светлой и темной темы (Dark Mode).
  ============================================================

  ------------------------------------------------------------
  ОСОБЕННОСТИ
  ------------------------------------------------------------
    • Без мигания (No FOUC): Требует установки скрипта в <head>.
    • Запоминает выбор в localStorage.
    • Уважает системные настройки (prefers-color-scheme).
    • Плавная анимация иконок (Солнце/Луна).
    • Полная поддержка i18n (переводы передаются в JS-класс).

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - class : string – Доп. классы для кнопки.

  ============================================================
  ВАЖНАЯ ИНСТРУКЦИЯ (Чтобы не было мигания темы при загрузке)
  ============================================================
  Добавь этот скрипт в <head> твоего шаблона (header.blade.php),
  ДО подключения основных стилей:

  <script>
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  </script>

  ============================================================
  ПРИМЕР ИСПОЛЬЗОВАНИЯ
  ============================================================

  <x-theme-toggle />
--}}

@props([
    'class' => null,
])

<button
    type="button"
    x-data="themeToggle({
        labelLight: '{{ __('Light mode', 'weblegko') }}',
        labelDark: '{{ __('Dark mode', 'weblegko') }}',
        onChange: (isDark) => { 
            // Callback on theme change (e.g., update charts)
            // console.log('Theme changed to:', isDark ? 'Dark' : 'Light');
        }
    })"
    @click="toggle()"
    :aria-label="currentLabel"
    :title="currentLabel"
    class="{{ cn(
        'relative inline-flex items-center justify-center w-10 h-10 rounded-full transition-colors',
        'text-muted-foreground hover:bg-accent hover:text-accent-foreground',
        'focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ring-offset-background',
        $class,
    ) }}">
    {{-- Иконка Солнца (показывается в темной теме) --}}
    <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 rotate-90 scale-50" x-transition:enter-end="opacity-100 rotate-0 scale-100">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>

    {{-- Иконка Луны (показывается в светлой теме) --}}
    <svg x-show="!isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -rotate-90 scale-50" x-transition:enter-end="opacity-100 rotate-0 scale-100">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
</button>