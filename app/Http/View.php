<?php

namespace App\Http;

class View
{
    public static function render(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $view_file = __DIR__ . "/../Views/{$template}.php";

        if (!file_exists($view_file)) {
            http_response_code(500);
            echo "View file {$view_file} not found";
            return;
        }

        include __DIR__ . "/../Views/layout.php";
    }
}
