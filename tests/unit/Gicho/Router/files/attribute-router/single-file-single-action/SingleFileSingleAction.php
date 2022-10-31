<?php

use Gicho\Controller\Controller;
use Gicho\Router\RouteDefinition;

class SingleFileSingleAction implements Controller
{
    #[RouteDefinition('/single', 'GET')]
    public function single()
    {
        return [];
    }
}
