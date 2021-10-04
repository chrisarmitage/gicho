<?php

namespace Gicho\Controller\Person;

use Gicho\Controller;
use Gicho\Repository\User;

class Index implements Controller
{
    public function dispatch()
    {
        $repository = new User();

        return $repository->getAll();
    }
}
