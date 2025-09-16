<?php

namespace App\Http;

class View
{
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public function render(string $template, array $data = []): string
    {
        $view_file = "{$this->basePath}/{$template}.php";
        $layoutFile = "{$this->basePath}/layout.php";

        if (!file_exists($view_file)) {
            throw new \InvalidArgumentException("View file not found: {$view_file}");
        }

        if (!file_exists($layoutFile)) {
            throw new \InvalidArgumentException("Layout file not found: {$layoutFile}");
        }

        ob_start();
        extract($data, EXTR_SKIP);
        include $layoutFile; // This will in turn include the $view_file
        return ob_get_clean();
    }
}
