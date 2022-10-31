<?php

namespace Gicho\Router;

use Gicho\Route;

class RegexRoute implements Route
{
    /**
     * @var string
     */
    protected $controllerName;

    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var string[]
     */
    protected $params = [];

    /**
     * @param string $controllerName
     */
    public function __construct(string $controllerName, string $methodName)
    {
        $this->controllerName = $controllerName;
        $this->methodName = $methodName;
    }

    /**
     * @return string
     */
    public function getControllerName() : string
    {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @param string[] $params
     * @return RegexRoute
     */
    public function setParams(array $params) : self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getParams() : array
    {
        return $this->params;
    }
}
