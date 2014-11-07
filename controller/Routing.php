<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-08
 * Time: 10:26
 */
/*
 * Here we will handle routing
 * You send in an hash with the querystring that you want to match,
 * and then a controller that you want to handle that query
 *
 */
require_once("RouteList.php");
require_once("RouteMatch.php");

class Route {
    private $routes;

    public function __construct($routes) {
        //Empty array would be hard to do something with
        assert(count($routes) !== 0, "You can't pass in an empty route array, you have to provide atleast one route");

        $this->routes = $routes;
    }

    /*
     * Calls the controller that is matched by the querystring, and passes with it the matched variables
     * However, it doesn't do anything fancy with it, so you have to do it all yourself
     */
    public function handleRoute() {
        foreach ($this->routes as $routingDirective) {
            //If the regex or the controllername is empty, then there is no way to do this.
            assert(!empty($routingDirective->regex) && !empty($routingDirective->controllername),
                   "You must provide a regular expression and a controller pair");

            //If the route is matched, we return the routingdirective, with all its information and also all the matched
            //parameters so that the controller can do something with them
            if (preg_match($routingDirective->regex, View::getRequestURI(), $matches)) {
                return new RouteMatch($routingDirective, $matches);
            }
        }
    }
}
