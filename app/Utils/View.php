<?php

namespace App\Utils;

class View
{
    public static function render(string $template, array $vars = []): void {
        extract($vars, EXTR_SKIP);
        include $template;
    }
}