<?php

namespace App;

class router
{
    private array $routes = [];

    public function get(string $path, array $handler)
    {
        $this->routes['GET'][$path] = $handler;

    }

    public function post(string $path, array $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }
    public function delete(string $path, array $handler)
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler && is_array($handler) && count($handler) === 2) {
            [$class, $methodName] = $handler;

            if (class_exists($class) && method_exists($class, $methodName)) {
                $controller = new $class();
                $controller->$methodName();
                return;
            }
        }
    }

}
