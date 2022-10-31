<?php

namespace Gicho\Router;

use Attribute;

#[Attribute]
class RouteDefinition
{
    public string $url;
    public string $method;

    /**
     * @param string $url
     * @param string $method
     */
    public function __construct(string $url, string $method)
    {
        $this->url = $url;
        $this->method = $method;
    }
}
