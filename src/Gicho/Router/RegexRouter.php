<?php

namespace Gicho\Router;

use Gicho\Router;

class RegexRouter implements Router
{
    protected $controllerNamespace;

    protected $routes;

    /**
     * @param string|null $controllerNamespace
     */
    public function __construct(?string $controllerNamespace)
    {
        $this->controllerNamespace = $controllerNamespace ?? 'Application\\Controller\\';
    }

    public function addRoute(string $url, string $controller)
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

        $this->routes[$url] = $controller;

        return $this;
    }

    /**
     * @param string $url
     * @param string $method
     * @return RegexRoute
     * @throws \Exception Cannot find route
     */
    public function getRouteForUrl(string $url, string $method) : RegexRoute
    {
        foreach ($this->routes as $route => $controller) {
            if (preg_match($route, $url, $matches)) {
                $foundRoute = new RegexRoute($this->controllerNamespace . $controller, 'dispatch');

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
}
