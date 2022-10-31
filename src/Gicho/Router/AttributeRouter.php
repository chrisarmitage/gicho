<?php

namespace Gicho\Router;

use Gicho\Controller\Controller;
use Gicho\Router;
use ReflectionClass;

class AttributeRouter implements Router
{
    private string $baseDir;

    protected $routes = [];

    public function __construct(string $baseDir)
    {
        $this->baseDir = $baseDir;
    }

    public function init()
    {
        $loader = new \Nette\Loaders\RobotLoader;
        $loader->addDirectory($this->baseDir);

        // Scans directories for classes / interfaces / traits
        $loader->rebuild();

        // Returns array of class => filename pairs
        $mappedClasses = $loader->getIndexedClasses();

        foreach ($mappedClasses as $className => $filename) {

            if (in_array(Controller::class, class_implements($className))) {
                $reflectionClass = new ReflectionClass($className);

                foreach ($reflectionClass->getMethods() as $method) {
                    $attributes = $method->getAttributes(RouteDefinition::class);

                    foreach ($attributes as $attribute) {
                        $routeDefinition = $attribute->newInstance();

                        $this->addRoute(
                            $routeDefinition->method,
                            $routeDefinition->url,
                            $className . '::' .  $method->getName()
                        );

                    }
                }
            }
        }
    }

    protected function addRoute(string $method, string $url, string $controller)
    {
        preg_match_all('/{(?:int:)?(?<paramName>.+?)}/', $url, $wildcardMatches);

        if (array_key_exists('paramName', $wildcardMatches)) {

            foreach ($wildcardMatches['paramName'] as $wildcardMatch) {
                $url = preg_replace(
                    [
                        '/{int:' . $wildcardMatch . '}/',
                        '/{' . $wildcardMatch . '}/',
                    ],
                    [
                        '(?<' . $wildcardMatch . '>[0-9]+)',
                        '(?<' . $wildcardMatch . '>[0-9A-Za-z\-_]+)',
                    ],
                    $url
                );
            }
        }

        $url = '!^' . $url . '$!';

        $this->routes[$method . '| ' . $url] = $controller;

        return $this;
    }
    
    public function getRouteForUrl(string $url, string $method)
    {
        foreach ($this->routes as $route => $controller) {
            [ $routeMethod, $routeUrl ] = explode('|', $route);


            if ($routeMethod === $method && preg_match($routeUrl, $url, $matches)) {
                [ $controllerName, $methodName ] = explode('::', $controller);
                $foundRoute = new RegexRoute($controllerName, $methodName);

                $routeParameters = [];
                foreach ($matches as $key => $value) {
                    if (\is_int($key) === false) {
                        $routeParameters[$key] = $value;
                    }
                }
                $foundRoute->setParams($routeParameters);

                return $foundRoute;
            }
        }

        throw new \Exception('Cannot find route');
    }

    public function getAllRoutes(): array
    {
        return $this->routes;
    }
}
