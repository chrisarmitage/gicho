<?php

namespace Gicho\Router;

use Gicho\Router;

class RpcRouter implements Router
{
    protected $controllerNamespace;

    /**
     * @param string|null $controllerNamespace
     */
    public function __construct(?string $controllerNamespace)
    {
        $this->controllerNamespace = $controllerNamespace ?? 'Application\\Controller\\';
    }


    /**
     * @param string $url
     * @param string $method
     * @return RpcRoute
     */
    public function getRouteForUrl(string $url, string $method) : RpcRoute
    {
        $url = parse_url($url);

        preg_match_all("#/(?<controller>[\w\-]+)#", $url['path'], $matches);

        if (count($matches['controller']) === 0) {
            $matches['controller'][] = 'index';
        }

        $controllerElements = array_map(
            function($url) {
                return str_replace(
                    ' ',
                    '',
                    ucwords(
                        str_replace(
                            '-',
                            ' ',
                            strtolower($url)
                        )
                    )
                );
            },
            $matches['controller']
        );

        return new RpcRoute(
            $this->controllerNamespace . implode('\\', $controllerElements)
        );
    }
}
