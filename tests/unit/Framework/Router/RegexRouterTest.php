<?php

class RegexRouterTest extends \PHPUnit\Framework\TestCase
{
    public function testParsesSimpleUrl()
    {
        $router = new \Framework\Router\RegexRouter('Framework\\Controller\\');
        $router->addRoute('/single', 'SingleController');

        $route = $router->getRouteForUrl('/single', 'GET');

        self::assertEquals('Framework\\Controller\\SingleController', $route->getControllerName());
    }

    public function testParsesNestedUrl()
    {
        $router = new \Framework\Router\RegexRouter('Framework\\Controller\\');
        $router->addRoute('/single/double', 'DoubleController');

        $route = $router->getRouteForUrl('/single/double', 'GET');

        self::assertEquals('Framework\\Controller\\DoubleController', $route->getControllerName());
    }

    public function testParsesUrlWithInt()
    {
        $router = new \Framework\Router\RegexRouter('Framework\\Controller\\');
        $router->addRoute('/single/{int:id}', 'SingleController');

        $route = $router->getRouteForUrl('/single/5', 'GET');

        self::assertEquals('Framework\\Controller\\SingleController', $route->getControllerName());
        self::assertEquals(['id' => 5], $route->getParams());
    }

    public function testParsesUrlWithMultipleInts()
    {
        $router = new \Framework\Router\RegexRouter('Framework\\Controller\\');
        $router->addRoute('/single/{int:id}/double/{int:dbl}/{int:tpl}', 'SingleController');

        $route = $router->getRouteForUrl('/single/5/double/6/7', 'GET');

        self::assertEquals('Framework\\Controller\\SingleController', $route->getControllerName());
        self::assertEquals(
            [
                'id' => 5,
                'dbl' => 6,
                'tpl' => 7,
            ],
            $route->getParams()
        );
    }

    public function testParsesUrlWithMultipleStrings()
    {
        $router = new \Framework\Router\RegexRouter('Framework\\Controller\\');
        $router->addRoute('/single/{id}/double/{dbl}/{tpl}', 'SingleController');

        $route = $router->getRouteForUrl('/single/param-id/double/dbl/last-param', 'GET');

        self::assertEquals('Framework\\Controller\\SingleController', $route->getControllerName());
        self::assertEquals(
            [
                'id' => 'param-id',
                'dbl' => 'dbl',
                'tpl' => 'last-param',
            ],
            $route->getParams()
        );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Cannot find route
     */
    public function testThrowsExceptionWhenRouteNotFound()
    {
        $router = new \Framework\Router\RegexRouter('Framework\\Controller\\');
        $router->addRoute('/single', 'SingleController');

        $router->getRouteForUrl('/other', 'GET');
    }
}
