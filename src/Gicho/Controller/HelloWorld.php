<?php

namespace Gicho\Controller;

use Gicho\Controller;

class HelloWorld implements Controller
{
    public function dispatch()
    {
        return 'Index';
    }
}
