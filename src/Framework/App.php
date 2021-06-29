<?php

namespace Framework;

use Auryn\Injector;
use Framework\Router\RpcRouter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var ControllerResolver
     */
    protected $controllerResolver;

    /**
     * @var Injector
     */
    protected $container;

    public function __construct()
    {
        $container = new \Auryn\Injector();

        $container->share($container);

        $container->share(new RpcRouter(null));

        $container->alias(\Framework\Router::class, RpcRouter::class);

        $router = $container->make(Router::class);
        $controllerResolver = $container->make(ControllerResolver::class);

        $this->router = $router;
        $this->controllerResolver = $controllerResolver;
        $this->container = $container;
    }

    public function run(): void
    {
        $request = Request::createFromGlobals();

        $route = $this->router->getRouteForUrl($request->getPathInfo(), $request->getMethod());

        $this->container->share($route);

        $controller = $this->controllerResolver->resolve($route->getControllerName());

        $controllerResponse = $controller->dispatch(...array_values($route->getParams()));

        if ($controllerResponse instanceof Response === false) {
            $response = new Response(
                json_encode($controllerResponse),
                Response::HTTP_OK,
                [
                    'content-type' => 'application/json',
                ]
            );
        } else {
            $response = $controllerResponse;
        }

        $response->prepare($request);

        $response->send();
    }

    public function console()
    {
        $args = $_SERVER['argv'];

        // Inline code
        $command = $args[1];

        $commandName = ucfirst($command);

        $actual = $this->container->make('Application\\Console\\' . $commandName);

        $actual->execute();
    }
}
