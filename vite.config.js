import { defineConfig, loadEnv } from 'vite'; // Добавили loadEnv
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin';

export default defineConfig(({ mode }) => {
  // 1. Загружаем переменные окружения из .env файла.
  // Третий аргумент '' (пустая строка) говорит Vite загрузить ВСЕ переменные,
  // а не только те, что начинаются на VITE_ (по умолчанию Vite отдает только VITE_).
  const env = loadEnv(mode, process.cwd(), '');

  return {
    base: '/wp-content/themes/ecomsys-blade/public/build/',
    plugins: [
      tailwindcss(),
      laravel({
        input: [
          'resources/css/app.css',
          'resources/js/app.js',
          'resources/css/editor.css',
          'resources/js/editor.js',
        ],
        refresh: true,
        assets: [
          'resources/images/webp/**', // только webp попадают в билд
          'resources/fonts/WOFF2/**', // только woff2 попадают в билд
          'resources/icons/sprite/**', // только спрайт попадает в билд
        ],
      }),

      wordpressPlugin(),

      wordpressThemeJson({
        disableTailwindColors: false,
        disableTailwindFonts: false,
        disableTailwindFontSizes: false,
        disableTailwindBorderRadius: false,
      }),
    ],
    server: {
      // 2. Берем APP_URL из загруженного env
      open: env.APP_URL || 'http://localhost',
      fs: {
        allow: ['resources'],
      },
    },
    resolve: {
      alias: {
        '@scripts': '/resources/js',
        '@styles': '/resources/css',
        '@fonts': '/resources/fonts',
        '@images': '/resources/images',
        '@icons': '/resources/icons',
      },
    },
  };
});
