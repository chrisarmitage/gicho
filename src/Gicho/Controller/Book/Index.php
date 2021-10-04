<?php

namespace Gicho\Controller\Book;

use Gicho\Controller;
use Gicho\Repository\Book;
use Gicho\Router\RestRoute;

class Index implements Controller
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
        $repository = new Book();

        return $repository->getAll($this->route->getNestedResources()['Person']);
    }
}
