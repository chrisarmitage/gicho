<?php

class RestRouterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Framework\Router\RestRouter
     */
    protected $router;

    public function setUp()
    {
        $this->router = new \Framework\Router\RestRouter('Framework\\Controller\\');
    }

    public function testParsesUrlWithNoResource() : void
    {
        $controllerName = $this->router->getRouteForUrl('/', 'GET');

        self::assertEquals('Framework\\Controller\\Root\\Index', $controllerName->getControllerName());
        self::assertEquals(null, $controllerName->getResourceId());
    }

    public function testParsesRootResourceUrl()
    {
        $controllerName = $this->router->getRouteForUrl('/resource', 'GET');

        self::assertEquals('Framework\\Controller\\Resource\\Index', $controllerName->getControllerName());
        self::assertEquals(null, $controllerName->getResourceId());
    }

    public function testParsesResourceWithIdUrl()
    {
        $controllerName = $this->router->getRouteForUrl('/resource/1', 'GET');

        self::assertEquals('Framework\\Controller\\Resource\\Read', $controllerName->getControllerName());
        self::assertEquals('1', $controllerName->getResourceId());
    }

    public function testIgnoresCaseOnResource()
    {
        $controllerName = $this->router->getRouteForUrl('/RESOURCE', 'GET');

        self::assertEquals('Framework\\Controller\\Resource\\Index', $controllerName->getControllerName());
        self::assertEquals(null, $controllerName->getResourceId());
    }

    public function testPreservesCaseOnResourceId()
    {
        $controllerName = $this->router->getRouteForUrl('/RESOURCE/ID', 'GET');

        self::assertEquals('Framework\\Controller\\Resource\\Read', $controllerName->getControllerName());
        self::assertEquals('ID', $controllerName->getResourceId());
    }

    public function testProcessesResourceDashes()
    {
        $controllerName = $this->router->getRouteForUrl('/resource-name', 'GET');

        self::assertEquals('Framework\\Controller\\ResourceName\\Index', $controllerName->getControllerName());
    }

    public function testPreservesResourceIdDashes()
    {
        $controllerName = $this->router->getRouteForUrl('/resource-id/dash-id', 'GET');

        self::assertEquals('Framework\\Controller\\ResourceId\\Read', $controllerName->getControllerName());
        self::assertEquals('dash-id', $controllerName->getResourceId());
    }

    public function testParsesNestedRootResourceUrl()
    {
        $controllerName = $this->router->getRouteForUrl('/resource/1/sub-resource', 'GET');

        self::assertEquals('Framework\\Controller\\SubResource\\Index', $controllerName->getControllerName());
        self::assertEquals(null, $controllerName->getResourceId());
        self::assertCount(1, $controllerName->getNestedResources());
        self::assertEquals('1', $controllerName->getNestedResources()['Resource']);
    }

    public function testParsesNestedResourceUrl()
    {
        $controllerName = $this->router->getRouteForUrl('/resource/1/sub-resource/2', 'GET');

        self::assertEquals('Framework\\Controller\\SubResource\\Read', $controllerName->getControllerName());
        self::assertEquals('2', $controllerName->getResourceId());
        self::assertCount(1, $controllerName->getNestedResources());
        self::assertEquals('1', $controllerName->getNestedResources()['Resource']);
    }

    public function testParsesMultiNestedRootResourceUrl()
    {
        $controllerName = $this->router->getRouteForUrl('/resource/1/sub-resource/2/sub-sub-resource', 'GET');

        self::assertEquals('Framework\\Controller\\SubSubResource\\Index', $controllerName->getControllerName());
        self::assertEquals(null, $controllerName->getResourceId());
        self::assertCount(2, $controllerName->getNestedResources());
        self::assertEquals('1', $controllerName->getNestedResources()['Resource']);
        self::assertEquals('2', $controllerName->getNestedResources()['SubResource']);
    }

    public function testParsesMultiNestedResourceUrl()
    {
        $controllerName = $this->router->getRouteForUrl('/resource/1/sub-resource/2/sub-sub-resource/3', 'GET');

        self::assertEquals('Framework\\Controller\\SubSubResource\\Read', $controllerName->getControllerName());
        self::assertEquals('3', $controllerName->getResourceId());
        self::assertCount(2, $controllerName->getNestedResources());
        self::assertEquals('1', $controllerName->getNestedResources()['Resource']);
        self::assertEquals('2', $controllerName->getNestedResources()['SubResource']);
    }
}
