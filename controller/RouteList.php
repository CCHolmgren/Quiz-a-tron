<?php

/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-11-01
 * Time: 11:00
 */
class RouteList implements Iterator {
    private $routes;

    public function __construct() {
        $this->routes = func_get_args();
    }

    public function rewind() {
        reset($this->routes);
    }

    public function current() {
        return current($this->routes);
    }

    public function key() {
        return key($this->routes);
    }

    public function next() {
        return next($this->routes);
    }

    public function valid() {
        $key = key($this->routes);
        $var = ($key !== null && $key !== false);

        return $var;
    }
}