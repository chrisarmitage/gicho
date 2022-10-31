<?php

namespace Gicho;

use Auryn\Injector;

class ControllerResolver
{
    /**
     * @var Injector
     */
    protected $container;

    /**
     * @param Injector $container
     */
    public function __construct(Injector $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $controllerName
     * @return Controller
     * @throws \Exception
     */
    public function resolve($controllerName, $methodName)
    {
        if (!method_exists($controllerName, $methodName)) {
            throw new \Exception("Could not find controller: {$controllerName}");
        }

        return $this->container->make($controllerName);
    }
}
