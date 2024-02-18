<?php

namespace Framework;

class Router {

    private array $routes = [];
    private array $middlewares = [];

    public function add(string $method, string $path, array $controller, array $middlewares){
        $this->routes[] = [
            'path' => $this->normalizePath($path),
            'method' => strtoupper($method),
            'controller' => $controller,
            'middlewares' => $middlewares
        ];
        return $this;
    }

    private function normalizePath(string $path): string{

        $path = '/'. $path . '/';
        $path = preg_replace('#[/]{2,}#','/',$path);

        return $path;
    }

    public function dispatch(string $path, string $method, Container $container = null){
        $path = $this->normalizePath($path);
        $method = strtoupper($method);
        foreach($this->routes as $route){
            if($method !== $route['method'] || !preg_match("#^{$route['path']}$#", $path)){
                continue;
            }
            
            [$class, $function] = $route['controller'];

            $controllerInstance = $container ? $container->resolve($class) : new $class;

            $action = fn () => $controllerInstance->{$function}();

            $allMiddlewares = [...$route['middlewares'], ...$this->middlewares];

            foreach($allMiddlewares as $middleware){

                $middlewareInstance = $container ? $container->resolve($middleware) : new $middleware;
                $action = fn () => $middlewareInstance->process($action);

            }

            $action();

            return;
        }
    }

    public function addMiddleware(string $middleware){
        $this->middlewares[] = $middleware;
    }

    public function addRouteMiddleware(string $middleware){
        $lastRouteKey = array_key_last($this->routes);
        $this->routes[$lastRouteKey]['middlewares'][] = $middleware;
    }
}