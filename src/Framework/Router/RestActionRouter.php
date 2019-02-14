<?php

namespace Framework\Router;

use Framework\Router;

class RestActionRouter implements Router
{
    protected $controllerNamespace;

    protected $methodActions = [
        'GET' => 'Index',
        'POST' => 'Create',
        'PUT' => 'Update',
        'DELETE' => 'Delete',
    ];

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
     * @return RestRoute
     */
    public function getRouteForUrl(string $url, string $method) : RestRoute
    {
        $url = parse_url($url);

        preg_match_all("#/(?<resource>[\w\-]+)(?:/(?<id>(?!actions)[\w\-]+))?(?:/actions/(?<action>[\w\-]+))?#", $url['path'], $matches);

        $resourceElements = array_map(
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
            $matches['resource']
        );

        $resourceName = $this->controllerNamespace . array_pop($resourceElements);
        $controllerType = $this->methodActions[$method];
        $resourceId = (\count($matches['resource']) === \count($matches['id']))
            ? array_pop($matches['id'])
            : null;

        if ($resourceName === $this->controllerNamespace) {
            $resourceName .= 'Root';
            $resourceId = '';
        }

        if ($controllerType === 'Index' && $resourceId !== '') {
            $controllerType = 'Read';
        }

        if ($method === 'POST' && \count($matches['action']) >= 1) {
            $action =  str_replace(
                ' ',
                '',
                ucwords(
                    str_replace(
                        '-',
                        ' ',
                        strtolower(array_pop($matches['action']))
                    )
                )
            );

            $controllerType = 'Action\\' . $action;
        }
        $controllerName = $resourceName . '\\' . $controllerType;

        $nestedResources = [];
        foreach ($resourceElements as $key => $value) {
            $nestedResources[$value] = $matches['id'][$key];
        }

        return new RestRoute($controllerName, $resourceId, $nestedResources);
    }
}
