<?php

require_once __DIR__ . '/files/attribute-router/single-file-single-action/SingleFileSingleAction.php';
require_once __DIR__ . '/files/attribute-router/single-file-multiple-actions/SingleFileMultipleActions.php';
require_once __DIR__ . '/files/attribute-router/multiple-file-multiple-actions/FirstFileMultipleActions.php';
require_once __DIR__ . '/files/attribute-router/multiple-file-multiple-actions/SecondFileMultipleActions.php';

class AttributeRouterTest extends \PHPUnit\Framework\TestCase
{
    public function testCreatesRoutingTableForSingleFileSingleAction()
    {
        $router = new \Gicho\Router\AttributeRouter(__DIR__ . '/files/attribute-router/single-file-single-action/');
        $router->init();
        $controllerName = $router->getRouteForUrl('/single', 'GET');

        self::assertEquals('SingleFileSingleAction', $controllerName->getControllerName());
        self::assertEquals('single', $controllerName->getMethodName());
    }

    public function testCreatesRoutingTableForSingleFileMultipleActions()
    {
        $router = new \Gicho\Router\AttributeRouter(__DIR__ . '/files/attribute-router/single-file-multiple-actions/');
        $router->init();

        $controllerName = $router->getRouteForUrl('/single', 'GET');
        self::assertEquals('SingleFileMultipleActions', $controllerName->getControllerName());
        self::assertEquals('getSingle', $controllerName->getMethodName());


        $controllerName = $router->getRouteForUrl('/multiple', 'GET');
        self::assertEquals('SingleFileMultipleActions', $controllerName->getControllerName());
        self::assertEquals('getMultiple', $controllerName->getMethodName());


        $controllerName = $router->getRouteForUrl('/single', 'POST');
        self::assertEquals('SingleFileMultipleActions', $controllerName->getControllerName());
        self::assertEquals('postSingle', $controllerName->getMethodName());
    }

    public function testCreatesRoutingTableForMultipleFilesMultipleActions()
    {
        $router = new \Gicho\Router\AttributeRouter(__DIR__ . '/files/attribute-router/multiple-file-multiple-actions/');
        $router->init();

        $controllerName = $router->getRouteForUrl('/first-single', 'GET');
        self::assertEquals('FirstFileMultipleActions', $controllerName->getControllerName());
        self::assertEquals('getSingle', $controllerName->getMethodName());

        $controllerName = $router->getRouteForUrl('/first-multiple', 'GET');
        self::assertEquals('FirstFileMultipleActions', $controllerName->getControllerName());
        self::assertEquals('getMultiple', $controllerName->getMethodName());

        $controllerName = $router->getRouteForUrl('/first-single', 'POST');
        self::assertEquals('FirstFileMultipleActions', $controllerName->getControllerName());
        self::assertEquals('postSingle', $controllerName->getMethodName());


        $controllerName = $router->getRouteForUrl('/second-single', 'GET');
        self::assertEquals('SecondFileMultipleActions', $controllerName->getControllerName());
        self::assertEquals('getSingle', $controllerName->getMethodName());

        $controllerName = $router->getRouteForUrl('/second-multiple', 'GET');
        self::assertEquals('SecondFileMultipleActions', $controllerName->getControllerName());
        self::assertEquals('getMultiple', $controllerName->getMethodName());

        $controllerName = $router->getRouteForUrl('/second-single', 'POST');
        self::assertEquals('SecondFileMultipleActions', $controllerName->getControllerName());
        self::assertEquals('postSingle', $controllerName->getMethodName());
    }
}
