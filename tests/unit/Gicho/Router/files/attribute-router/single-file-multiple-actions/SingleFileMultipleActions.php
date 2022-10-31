<?php

use Gicho\Controller\Controller;
use Gicho\Router\RouteDefinition;

class SingleFileMultipleActions implements Controller
{
    #[RouteDefinition('/single', 'GET')]
    public function getSingle()
    {
        return [];
    }

    #[RouteDefinition('/multiple', 'GET')]
    public function getMultiple()
    {
        return [];
    }

    #[RouteDefinition('/single', 'POST')]
    public function postSingle()
    {
        return [];
    }
}
