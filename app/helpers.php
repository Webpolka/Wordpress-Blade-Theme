<?php

use TailwindMerge\TailwindMerge;

if (! function_exists('cn')) {
    /**
     * Умное слияние Tailwind классов (clsx + twMerge)
     * Использует gehrisro/tailwind-merge-php
     *
     * @param mixed ...$classes Строки, массивы или условные выражения
     * @return string
     */
    function cn(...$classes)
    {
        static $tw = null;

        if ($tw === null) {
            try {
                $tw = TailwindMerge::instance();
            } catch (Throwable $e) {
                // Fallback если библиотека упала — просто склеиваем как есть
                $filtered = array_filter($classes, fn($c) => ! empty($c) && is_string($c));
                return implode(' ', $filtered);
            }
        }

        // Рекурсивно разбираем массивы и фильтруем пустоту
        $filtered = [];
        foreach ($classes as $class) {
            if (is_array($class)) {
                // Если передан массив, проваливаемся в него
                $filtered[] = cn(...$class);
            } elseif (is_string($class) && trim($class) !== '') {
                $filtered[] = $class;
            }
        }

        if (empty($filtered)) {
            return '';
        }

        // Схлопываем конфликты Tailwind (px-4 + px-8 = px-8)
        return $tw->merge(implode(' ', $filtered));
    }
}