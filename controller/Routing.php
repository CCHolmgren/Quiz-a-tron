<?php
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
 *
 *
 *
 */
class Route{
    private $routes;
    public function __construct($routes){
        assert(count($routes) !== 0,"You can't pass in an empty route array, you have to provide atleast one route");

        $this->routes = $routes;
    }
    /*
     * Calls the controller that is matched by the querystring, and passes with it the matched variables
     * However, it doesn't do anything fancy with it, so you have to do it all yourself
     */
    public function handleRoute(){
        foreach($this->routes as $routingDirective){
            assert(!empty($routingDirective->regex)&&!empty($routingDirective->controllername),
                "You have to provide a regular expression and a controller pair");

            //var_dump($routingDirective->regex,$_SERVER["QUERY_STRING"],preg_match($routingDirective->regex, $_SERVER["QUERY_STRING"]));
            if(preg_match($routingDirective->regex, $_SERVER["QUERY_STRING"],$matches)){
                return array("routingdirective" => $routingDirective, "matches" => $matches);
            }
        }
    }
}