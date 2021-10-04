<?php

namespace Gicho;

interface Route
{
    /**
     * @return string
     */
    public function getControllerName() : string;

    /**
     * @return string[]
     */
    public function getParams() : array;
}
