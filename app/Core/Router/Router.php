<?php

namespace App\Core\Router;

class Router
{
    protected $routes = [];

    public function add($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function get($path, $handler)
    {
        $this->add('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->add('POST', $path, $handler);
    }

    public function dispatch($method, $uri)
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            // Verificar match exato primeiro
            if ($route['method'] === $method && $route['path'] === $uri) {
                return $this->callHandler($route['handler'], []);
            }

            // Verificar match com parâmetros dinâmicos
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove o match completo
                return $this->callHandler($route['handler'], $matches);
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }

    protected function callHandler($handler, $params = [])
    {
        if (is_array($handler)) {
            [$controller, $method] = $handler;
            $controllerInstance = new $controller();
            return $controllerInstance->$method(...$params);
        }

        if (is_callable($handler)) {
            return $handler(...$params);
        }

        throw new \Exception("Invalid handler");
    }
}
