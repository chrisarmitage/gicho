<?php

class RpcRouterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Framework\Router\RpcRouter
     */
    protected $router;

    public function setUp()
    {
        $this->router = new \Framework\Router\RpcRouter('Framework\\Controller\\');
    }

    public function testParsesSingleDepthUrl()
    {
        $controllerName = $this->router->getRouteForUrl('/single', 'GET');

        self::assertEquals('Framework\\Controller\\Single', $controllerName->getControllerName());
    }

    public function testParsesMultiDepthUrl()
    {
        $controllerName = $this->router->getRouteForUrl('/one/two/three', 'GET');

        self::assertEquals('Framework\\Controller\\One\\Two\\Three', $controllerName->getControllerName());
    }

    public function testIgnoresCase()
    {
        $controllerName = $this->router->getRouteForUrl('/one/Two/THREE', 'GET');

        self::assertEquals('Framework\\Controller\\One\\Two\\Three', $controllerName->getControllerName());
    }

    public function testProcessesDashes()
    {
        $controllerName = $this->router->getRouteForUrl('/one-part/two/three-multi-part', 'GET');

        self::assertEquals('Framework\\Controller\\OnePart\\Two\\ThreeMultiPart', $controllerName->getControllerName());
    }
}
