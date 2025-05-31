<?php

namespace Routes;

class Route
{
    public $path;
    public $action;
    public $method;

    public function __construct($path, $action, $method)
    {
        $this->path = $path;
        $this->action = $action;
        $this->method = $method;
    }
}
