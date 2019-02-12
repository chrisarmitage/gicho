<?php

use Framework\App;
use Framework\Router\RestRouter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var App
     */
    protected $app;

    public function setUp() {
        $container = new \Auryn\Injector();

        $container->share($container);
        $container->share(new RestRouter('Framework\\Controller\\'));
        $container->alias(\Framework\Router::class, RestRouter::class);

        $this->app = $container->make(App::class);
    }

    public function testReturnsResponse()
    {
        $request = Request::create(
            '/person',
            'GET'
        );

        $response = $this->app->processRequest($request);

        self::assertInstanceOf(Response::class, $response);
        //self::assertContains('[Route: /hello-world]', $response->getContent());
        //self::assertContains('[Controller: Framework\Controller\HelloWorld]', $response->getContent());
        //self::assertContains('[Response: Index]', $response->getContent());
    }

}
