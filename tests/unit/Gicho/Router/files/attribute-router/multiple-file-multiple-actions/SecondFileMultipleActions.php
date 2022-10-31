<?php

use Gicho\Controller\Controller;
use Gicho\Router\RouteDefinition;

class SecondFileMultipleActions implements Controller
{
    #[RouteDefinition('/second-single', 'GET')]
    public function getSingle()
    {
        return [];
    }

    #[RouteDefinition('/second-multiple', 'GET')]
    public function getMultiple()
    {
        return [];
    }

    #[RouteDefinition('/second-single', 'POST')]
    public function postSingle()
    {
        return [];
    }
}
