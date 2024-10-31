<?php

// Esta función recibe un texto y uno o varios términos de búsqueda.
// Luego resalta todos los términos encontrados en el texto con un fondo de advertencia.

if (!function_exists('highlightKeyword')) {
    function highlightKeyword($text, $keywords) {
        if (!$keywords) return $text;

        $terms = is_array($keywords) ? $keywords : explode(' ', $keywords);

        $pattern = '/(' . implode('|', array_map('preg_quote', $terms)) . ')/i';

        return preg_replace($pattern, '<span class="bg-warning">$1</span>', $text);
    }
}
