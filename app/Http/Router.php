<?php
/*
 * Copyright (c) 2025.
 */

namespace App\Http;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute("GET", $path, $handler);
    }

    public function post(string $path, callable|array $handler)
    {
        $this->addRoute("POST", $path, $handler);
    }

    public function delete(string $path, callable|array $handler)
    {
        $this->addRoute("DELETE", $path, $handler);
    }

    private function addRoute(
        string $method,
        string $path,
        callable|array $handler,
    ): void {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(Request $request, Response $response): void
    {
        $method = $request->getMethod();
        $uri = $request->getPath();

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            $this->invokeHandler($handler, $request, $response);
            return;
        }

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern =
                "@^" .
                preg_replace("/\{([a-zA-Z0-9_]+)\}/", '(?P<$1>[^/]+)', $route) .
                "$@";
            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter(
                    $matches,
                    "is_string",
                    ARRAY_FILTER_USE_KEY,
                );
                $this->invokeHandler($handler, $request, $response, $params);
                return;
            }
        }

        // Not found
        $response->setStatus(404)->setBody("404 Not Found")->send();
    }

    private function invokeHandler(
        $handler,
        Request $req,
        Response $res,
        array $params = [],
    ): void {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            $controller->$method($req, $res, ...array_values($params));
        } elseif (is_callable($handler)) {
            $handler($req, $res, ...array_values($params));
        }
    }
}
