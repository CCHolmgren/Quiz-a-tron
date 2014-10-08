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
    public function __construct(array $routes){
        $this->routes = $routes;
    }
    /*
     * Calls the controller that is matched by the querystring, and passes with it the matched variables
     * However, it doesn't do anything fancy with it, so you have to do it all yourself
     */
    public function handleRoute(){
        foreach($this->routes as $queryString=>$controller){
            if(preg_match($queryString, $_SERVER["QUERY_STRING"],$matches)){
                var_dump( $controller,$matches);
                return array("controller" => $controller, "matches" => $matches);
            }
        }
    }
}