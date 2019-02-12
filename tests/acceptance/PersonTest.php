<?php

use Framework\App;
use Framework\Router\RestRouter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonTest extends \PHPUnit\Framework\TestCase
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

    public function testReturnsBookIndex()
    {
        $request = Request::create(
            '/person',
            'GET'
        );

        $response = $this->app->processRequest($request);

        self::assertInstanceOf(Response::class, $response);

        $payload = json_decode($response->getContent());

        self::assertEquals(JSON_ERROR_NONE, json_last_error(), 'Response was not valid JSON');

        self::assertCount(4, $payload);
        self::assertEquals(
            (object) [
                'username' => 'ca',
                'name' => 'Chris Armitage',
            ],
            $payload[0]
        );
    }

    public function testReturnsBook()
    {
        $request = Request::create(
            '/person/ca',
            'GET'
        );

        $response = $this->app->processRequest($request);

        self::assertInstanceOf(Response::class, $response);

        $payload = json_decode($response->getContent());

        self::assertEquals(JSON_ERROR_NONE, json_last_error(), 'Response was not valid JSON');

        self::assertEquals(
            (object) [
                'username' => 'ca',
                'name' => 'Chris Armitage',
            ],
            $payload
        );
    }

}
