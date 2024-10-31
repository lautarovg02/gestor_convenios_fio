<?php

if (!function_exists('highlightKeyword')) {
    function highlightKeyword($text, $keyword) {
        if (!$keyword) return $text; // Si no hay palabra clave, devuelve el texto original
        return preg_replace('/(' . preg_quote($keyword, '/') . ')/i', '<span class="bg-text-color">$1</span>', $text);
    }
}
