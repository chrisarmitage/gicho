<?php

namespace Gicho;

use Gicho\Router\RestRoute;
use Gicho\Router\RpcRoute;

interface Router
{
    /**
     * @param string $url
     * @param string $method
     * @return RpcRoute|RestRoute
     */
    public function getRouteForUrl(string $url, string $method);
}
