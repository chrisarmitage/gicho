<?php

namespace Gicho\Controller\Person;

use Gicho\Controller;
use Gicho\Repository\User;
use Gicho\Router\RestRoute;

class Read implements Controller
{
    /**
     * @var RestRoute
     */
    protected $route;

    /**
     * @param RestRoute $route
     */
    public function __construct(RestRoute $route)
    {
        $this->route = $route;
    }

    public function dispatch()
    {
        $repository = new User();

        return $repository->get($this->route->getResourceId());
    }
}
