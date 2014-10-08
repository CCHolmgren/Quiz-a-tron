<?php
define("__ROOT__", "");
require_once(__ROOT__."controller/Routing.php");
class thisismycontroller {
    static function Route(){
        echo "Hello there";
    }
}
$routes = array("/(?P<hej>whatnow)/"=>array("thisismycontroller","Route"));
$Routing = new Route($routes);
echo "<pre>";
$controller = $Routing->handleRoute();
var_dump($controller);
$func = new $controller["controller"][0];
var_dump($func);
$func->$controller["controller"][1]($controller["matches"]);

echo "</pre>";