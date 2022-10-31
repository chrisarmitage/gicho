<?php

use Gicho\Controller\Controller;
use Gicho\Router\RouteDefinition;

class FirstFileMultipleActions implements Controller
{
    #[RouteDefinition('/first-single', 'GET')]
    public function getSingle()
    {
        return [];
    }

    #[RouteDefinition('/first-multiple', 'GET')]
    public function getMultiple()
    {
        return [];
    }

    #[RouteDefinition('/first-single', 'POST')]
    public function postSingle()
    {
        return [];
    }
}
