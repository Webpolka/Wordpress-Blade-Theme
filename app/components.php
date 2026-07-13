<?php
/**
 * Подключаем хелперы компонентов
 *
 * @return void
 */

$componentHelpers = [
    get_theme_file_path('resources/views/components/dropdown-menu/build-tree-menu.php'),
    // Добавляй сюда другие компоненты по мере появления
];

foreach ($componentHelpers as $helper) {
    if (file_exists($helper)) {
        require_once $helper;
    }
}