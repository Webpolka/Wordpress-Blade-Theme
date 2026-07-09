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
            $tw = \TailwindMerge\TailwindMerge::instance();
        } catch (\Throwable $e) {
            $tw = null; // Fallback
        }
    }

    $filtered = [];
    
    foreach ($classes as $class) {
        if (is_array($class)) {
            foreach ($class as $key => $value) {
                // Если ключ строка — это условный класс: ['bg-red' => $isError]
                if (is_string($key)) {
                    if ($value) {
                        $filtered[] = $key;
                    }
                } 
                // Если ключ числовой — это обычный список: ['p-4', 'bg-red']
                else {
                    if (is_string($value) && trim($value) !== '') {
                        $filtered[] = $value;
                    } elseif (is_array($value)) {
                        // Рекурсивно обрабатываем вложенные массивы
                        $filtered[] = cn($value);
                    }
                }
            }
        } elseif (is_string($class) && trim($class) !== '') {
            $filtered[] = $class;
        }
    }

    if (empty($filtered)) {
        return '';
    }

    $result = implode(' ', $filtered);

    if ($tw) {
        return $tw->merge($result);
    }

    return $result; // Fallback если tailwind-merge не установлен
}
}