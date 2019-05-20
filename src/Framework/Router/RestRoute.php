<?php

namespace Framework\Router;

use Framework\Route;

class RestRoute implements Route
{
    /**
     * @var string
     */
    protected $controllerName;

    protected $resourceId;

    protected $nestedResources = [];

    /**
     * @param string $controllerName
     * @param       $resourceId
     * @param array $nestedResources
     */
    public function __construct(string $controllerName, $resourceId, array $nestedResources)
    {
        $this->controllerName = $controllerName;
        $this->resourceId = $resourceId;
        $this->nestedResources = $nestedResources;
    }

    /**
     * @return string
     */
    public function getControllerName() : string
    {
        return $this->controllerName;
    }

    /**
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @return array
     */
    public function getNestedResources()
    {
        return $this->nestedResources;
    }

    public function getParams(): array
    {
        return [];
    }
}
