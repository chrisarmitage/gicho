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
     * @var string[]
     */
    protected $params = [];

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
