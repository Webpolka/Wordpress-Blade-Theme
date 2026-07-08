## Обязательные плагины (Если не стоят — установи)

1.Laravel Blade Snippets - даст VS Code грамматику языка Blade
2.Blade Formatter — чтобы Shift+Alt+F красиво форматировало Blade, а не ломало его.
3.Tailwind CSS IntelliSense — автоподсказка классов.
4.PHP Intelephense — лучший автокомплит для PHP.
5.Prettier — для форматирования JS/JSON/CSS.
6.ESLint — для нашего CLI на Node.js.

# =============== Как использовать CLI для создания компонентов ? ===============

```bash
npm run cli install                      # уcтановить выборочно
npm run cli add button                   # добавить конкретный
npm run cli add all                      # добавить все из templates
npm run cli uninstall                    # удалить выборочно
npm run cli remove <component>           # удалить по названию
npm run cli remove-all                   # удалить все вместе с папкой
```

или выполни:

```bash
npm link                                 # привязать префикс wl
```

чтобы отключить ссылку :

```bash
npm unlink -g wl                         # отвязать префикс wl
```

и используй:

```bash
wl install                               # уcтановить выборочно
wl add button                            # добавить конкретный
wl add all                               # добавить все из templates
wl uninstall                             # удалить выборочно
wl remove <component>                    # удалить по названию
wl remove-all                            # удалить все вместе с папкой
```

# ============================= RESOURCES USAGE ====================================

## SVG используем только спрайты

```bash
 <svg class="w-8 h-8 text-black">
    <use href="#burger"></use>
</svg>
```

## IMAGES используем через манифест

```bash
src=" {{ Vite::asset('resources/images/webp/bg.webp') }} "
```

## FONTS используем с алиасами @fonts

```bash
 src: url('@fonts/WOFF2/Poppins-Regular.woff2') format('woff2');
```
