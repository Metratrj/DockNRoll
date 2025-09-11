<?php
/*
 * Copyright (c) 2025.
 */

namespace App\Http;

class Request
{
    public function __construct(
        public string $uri,
        public string $method,
        public array $get,
        public array $post,
        public array $files,
        public array $cookie,
        public array $server,
    ) {
    }

    public function getMethod(): string
    {
        return $_SERVER["REQUEST_METHOD"] ?? "GET";
    }

    public function getPath(): string
    {
        $uri = parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH);
        return rtrim($uri, "/") ?: "/";
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? ($_GET[$key] ?? $default);
    }

    public static function createFromGlobals()
    {
        return new static(
            $_SERVER["REQUEST_URI"],
            $_SERVER["REQUEST_METHOD"],
            $_GET,
            $_POST,
            $_FILES,
            $_COOKIE,
            $_SERVER,
        );
    }
}
