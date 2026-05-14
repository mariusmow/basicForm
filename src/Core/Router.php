<?php

namespace Marius\BasicForm\Core;

class Router
{
    protected array $routes = [];

    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    private function addRoute(string $method, string $path, array $handler, array $middleware = []): void
    {
        $path = rtrim($path, '/') ?: '/';
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';

        $route = $this->routes[$method][$path] ?? null;

        if (!$route) {
            header("HTTP/1.0 404 Not Found");
            echo json_encode(['error' => 'Route not found']);
            return;
        }

        foreach ($route['middleware'] as $middlewareClass) {
            if (!class_exists($middlewareClass)) {
                header('HTTP/1.0 500 Internal Server Error');
                echo json_encode(['error' => "Middleware $middlewareClass not found"]);
                return;
            }
            new $middlewareClass()->handle();
        }

        [$controllerClass, $methodName] = $route['handler'];

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $methodName)) {
                return $controller->$methodName();
            }
        }

        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode(['error' => 'Controller or Method not found']);
    }
}
