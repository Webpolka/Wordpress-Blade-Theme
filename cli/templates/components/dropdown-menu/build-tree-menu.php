<?php

/**
 * Построение дерева меню WordPress для компонента <x-dropdown-menu>.
 *
 * Файл: components/dropdown-menu/build-tree-menu.php
 */

// Защита от повторного объявления
if (function_exists('build_tree_menu')) {
    return;
}

/**
 * Получить меню WordPress в формате компонента <x-dropdown-menu>.
 *
 * @param  string  $location  Slug меню (например, 'primary')
 * @param  array   $options   Опции: ['cache' => true, 'cache_ttl' => 3600]
 * @return array               Массив вида: [['label' => ..., 'url' => ..., 'children' => [...]]]
 *
 * Пример:
 *   $menu = build_tree_menu('primary');
 *   <x-dropdown-menu :items="$menu" />
 *
 *   // С отключённым кешем (для dev):
 *   $menu = build_tree_menu('primary', ['cache' => false]);
 */
function build_tree_menu(string $location, array $options = []): array
{
    // Защита от вызова вне WordPress
    if (! function_exists('wp_get_nav_menu_items')) {
        return [];
    }

    $useCache = $options['cache'] ?? true;
    $cacheTtl = $options['cache_ttl'] ?? HOUR_IN_SECONDS;
    $cacheKey = "build_tree_menu_{$location}";

    // Кешируем результат (WordPress Object Cache)
    if ($useCache) {
        $cached = wp_cache_get($cacheKey, 'menu');
        if ($cached !== false) {
            return $cached;
        }
    }

    // Получаем меню по location
    $locations = get_nav_menu_locations();
    if (empty($locations[$location])) {
        return [];
    }

    $menu = wp_get_nav_menu_object($locations[$location]);
    if (! $menu) {
        return [];
    }

    $items = wp_get_nav_menu_items($menu->term_id);
    if (empty($items)) {
        return [];
    }

    // Группируем по parent
    $grouped = [];
    foreach ($items as $item) {
        $parentId = (int) ($item->menu_item_parent ?: 0);
        $grouped[$parentId][] = $item;
    }

    // Рекурсивная сборка дерева
    $buildTree = function (int $parentId = 0) use (&$buildTree, $grouped) {
        $tree = [];
        if (! isset($grouped[$parentId])) {
            return $tree;
        }

        foreach ($grouped[$parentId] as $item) {
            $tree[] = [
                'label'    => $item->title,
                'url'      => $item->url,
                'children' => $buildTree((int) $item->ID),
            ];
        }

        return $tree;
    };

    $result = $buildTree(0);

    // Сохраняем в кеш
    if ($useCache) {
        wp_cache_set($cacheKey, $result, 'menu', $cacheTtl);
    }

    return $result;
}

// Сброс кеша при изменении меню
add_action('wp_update_nav_menu', function () {
    wp_cache_delete('build_tree_menu_primary', 'menu');
    wp_cache_delete('build_tree_menu_footer', 'menu');
    // Или полностью: wp_cache_flush();
});
