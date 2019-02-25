<?php

namespace Framework\Router;

use Framework\Route;

class RpcRoute implements Route
{
    /**
     * @var string
     */
    protected $controllerName;

    /**
     * @param string $controllerName
     */
    public function __construct(string $controllerName)
    {
        $this->controllerName = $controllerName;
    }

    /**
     * @return string
     */
    public function getControllerName() : string
    {
        return $this->controllerName;
    }

    public function getParams(): array
    {
        return [];
    }

}
