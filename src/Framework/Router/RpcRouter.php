<?php

namespace Framework\Router;

use Framework\Router;

class RpcRouter implements Router
{
    protected $controllerNamespace = 'Framework\\Controller\\';

    /**
     * @param $url
     * @return RpcRoute
     */
    public function getRouteForUrl($url)
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
