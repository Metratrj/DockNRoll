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
        public array $body,
    ) {}

    public function getMethod(): string
    {
        return $this->method ?? "GET";
    }

    public function getPath(): string
    {
        $uri = parse_url($this->uri ?? "/", PHP_URL_PATH);
        return rtrim($uri, "/") ?: "/";
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? ($this->get[$key] ?? $default);
    }

    public static function createFromGlobals(): static
    {
        return new static(
            $_SERVER["REQUEST_URI"],
            $_SERVER["REQUEST_METHOD"],
            $_GET,
            $_POST,
            $_FILES,
            $_COOKIE,
            $_SERVER,
            json_decode(file_get_contents("php://input"), true) ?? [],
        );
    }
}
