<?php

namespace Framework;

use Framework\Router\RestRoute;
use Framework\Router\RpcRoute;

interface Router
{
    /**
     * @param string $url
     * @param string $method
     * @return RpcRoute|RestRoute
     */
    public function getRouteForUrl(string $url, string $method);
}
