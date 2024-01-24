<?php

namespace Framework;

class Router {

    private array $routes = [];

    public function add(string $method, string $path, array $controller){
        $this->routes[] = [
            'path' => $this->normalizePath($path),
            'method' => strtoupper($method),
            'controller' => $controller
        ];
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
            $controllerInstance->$function();
        }
    }
}