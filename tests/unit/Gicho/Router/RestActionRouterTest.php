<?php

require_once __DIR__ . '/RestRouterTest.php';

class RestActionRouterTest extends RestRouterTest
{
    /**
     * @var \Gicho\Router\RestActionRouter
     */
    protected $router;

    public function setUp(): void
    {
        $this->router = new \Gicho\Router\RestActionRouter('Framework\\Controller\\');
    }

    public function testParsesRootResourceWithAction() : void
    {
        $controllerName = $this->router->getRouteForUrl('/resource/actions/complete', 'POST');

        self::assertEquals('Framework\\Controller\\Resource\\Action\\Complete', $controllerName->getControllerName());
        self::assertEquals(null, $controllerName->getResourceId());
    }

    public function testParsesResourceWithIdWithAction() : void
    {
        $controllerName = $this->router->getRouteForUrl('/resource/1/actions/complete', 'POST');

        self::assertEquals('Framework\\Controller\\Resource\\Action\\Complete', $controllerName->getControllerName());
        self::assertEquals('1', $controllerName->getResourceId());
    }

    public function testIgnoresCaseOnAction() : void
    {
        $controllerName = $this->router->getRouteForUrl('/resource/1/actions/COMPLETE', 'POST');

        self::assertEquals('Framework\\Controller\\Resource\\Action\\Complete', $controllerName->getControllerName());
        self::assertEquals('1', $controllerName->getResourceId());
    }

    public function testProcessesActionDashes() : void
    {
        $controllerName = $this->router->getRouteForUrl('/resource/1/actions/complete-this', 'POST');

        self::assertEquals('Framework\\Controller\\Resource\\Action\\CompleteThis', $controllerName->getControllerName());
        self::assertEquals('1', $controllerName->getResourceId());
    }

    public function testParsesNestedRootResourceUrlWithAction() : void
    {
        $controllerName = $this->router->getRouteForUrl('/resource/1/sub-resource/actions/complete', 'POST');

        self::assertEquals('Framework\\Controller\\SubResource\\Action\\Complete', $controllerName->getControllerName());
        self::assertEquals(null, $controllerName->getResourceId());
        self::assertCount(1, $controllerName->getNestedResources());
        self::assertEquals('1', $controllerName->getNestedResources()['Resource']);
    }

    public function testParsesNestedResourceUrlWithAction() : void
    {
        $controllerName = $this->router->getRouteForUrl('/resource/1/sub-resource/2/actions/complete', 'POST');

        self::assertEquals('Framework\\Controller\\SubResource\\Action\\Complete', $controllerName->getControllerName());
        self::assertEquals('2', $controllerName->getResourceId());
        self::assertCount(1, $controllerName->getNestedResources());
        self::assertEquals('1', $controllerName->getNestedResources()['Resource']);
    }

    public function testParsesMultiNestedRootResourceUrl()
    {
        $controllerName = $this->router->getRouteForUrl('/resource/1/sub-resource/2/sub-sub-resource/actions/complete', 'POST');

        self::assertEquals('Framework\\Controller\\SubSubResource\\Action\\Complete', $controllerName->getControllerName());
        self::assertEquals(null, $controllerName->getResourceId());
        self::assertCount(2, $controllerName->getNestedResources());
        self::assertEquals('1', $controllerName->getNestedResources()['Resource']);
        self::assertEquals('2', $controllerName->getNestedResources()['SubResource']);
    }

    public function testParsesMultiNestedResourceUrl()
    {
        $controllerName = $this->router->getRouteForUrl('/resource/1/sub-resource/2/sub-sub-resource/3/actions/complete', 'POST');

        self::assertEquals('Framework\\Controller\\SubSubResource\\Action\\Complete', $controllerName->getControllerName());
        self::assertEquals('3', $controllerName->getResourceId());
        self::assertCount(2, $controllerName->getNestedResources());
        self::assertEquals('1', $controllerName->getNestedResources()['Resource']);
        self::assertEquals('2', $controllerName->getNestedResources()['SubResource']);
    }
}
