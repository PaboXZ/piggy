<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass;
use ReflectionNamedType;
use Framework\Exceptions\ContainerException;

class Container {

    private array $definitions = [];

    public function addDefinitions(array $newDefinitions){
        $this->definitions = [...$this->definitions, ...$newDefinitions];
    }

    public function resolve(string $className) {

        $reflectionClass = new ReflectionClass($className);

        if(!$reflectionClass->isInstantiable())
            throw new ContainerException("The {$className} is not instantiable");

        $constructor = $reflectionClass->getConstructor();
        
        if(!$constructor)
            return new $className;

        $params = $constructor->getParameters();

        if(count($params) === 0)
            return new $className;

        $depedencies = [];

        foreach($params as $param){
            $name = $param->getName();
            $type = $param->getType();

            if(!$type){
                throw new ContainerException("Cannot instantiate class {$className} because no type given to {$name} parameter");
            }

            if(!$type instanceof ReflectionNamedType || $type->isBuiltin())
                throw new ContainerException("Cannot instantiate class {$className}, invalid data type given to {$name} parameter");

            $depedencies[] = $this->get($type->getName());

            return $reflectionClass->newInstanceArgs($depedencies);
        }
    }

    private function get($id){
        if(!array_key_exists($id, $this->definitions))
            throw new ContainerException("Class {$id} does not exists in container definitions");

        $factory = $this->definitions[$id];
        $dependency = $factory();

        return $dependency;
    }
}