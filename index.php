<?php
/*
 * This page will be the entry point and the only page that will handle the app.
 * As such, it will have a switchstatement or something that will handle the routing and then hand away the control to
 * the controllers that handles the different functions (such as login, register, doing quizes, making quizes and such
 */

/*
 * Define the __ROOT__ global so that it won't be a real pain to move this to a webserver later
 */
define("__ROOT__", "C:/Users/Chrille/Desktop/PHP-projekt/");

/*
 * All requires that are needed by this page
 * We must do the requires first since we are storing the user in the session and as such it will get unserialized
 * And if it doesn't know what a UserModel is before the session is created, then it will break horribly
 */
require_once(__ROOT__."controller/Routing.php");
require_once(__ROOT__."controller/RegisterController.php");
require_once(__ROOT__."controller/LoginController.php");
require_once(__ROOT__."controller/LogoutController.php");
require_once(__ROOT__ . "controller/QuizController.php");
require_once(__ROOT__."controller/DefaultController.php");
require_once(__ROOT__."controller/RoutingDirective.php");

session_start();

class NotImplementedException extends Exception{}

/*
 * Define the RoutingDirectives so that the routing knows to what controller to send the controls to
 */
$routes = array(new RoutingDirective("/^\/register/", "RegisterController", "getHTML", "register"),
    new RoutingDirective("/^\/login/", "LoginController", "getHTML", "login"),
    new RoutingDirective("/^\/logout/", "LogoutController", "getHTML", "logout"),
    new RoutingDirective("/^\/quizes/", "QuizController", "getHTML", "quizes"),
    new RoutingDirective("/^\/?/", "DefaultController", "getHTML", "default"));

// The Route is a class that handles routes, at this moment it only does handleRoute, but maybe more in the future
$routing = new Route($routes);

// PHP is ugly and can't handle several argument returns, not as of 5.5 but from 5.6
// We use 5.5 here
$values = $routing->handleRoute();
$rd = $values["routingdirective"];
$matches = $values["matches"];

//$controller, $matches = $routing->handleRoute();

/*
 * This is an ugly way to handle stuff, but it works, doesn't it?
 */
$x = new $rd->controllername();
$fn = $rd->functionname;
echo $x->$fn(preg_replace($rd->regex, "", $_SERVER["QUERY_STRING"]));

/*
switch($rd->name){
    case "register":
        $x = new $rd->controllername();
        $fn = $rd->functionname;
        echo $x->$fn(preg_replace($rd->regex, "", $_SERVER["QUERY_STRING"]));
        //$x->($rd->$functionname)();
        break;
    case "login":
        $x = new $rd->controllername();
        $fn = $rd->functionname;
        echo $x->$fn(preg_replace($rd->regex, "", $_SERVER["QUERY_STRING"]));
        break;
    case "default":
        $x = new $rd->controllername();
        $fn = $rd->functionname;
        echo $x->$fn(preg_replace($rd->regex, "", $_SERVER["QUERY_STRING"]));
        break;
    default:
        break;
}
*/









/*
$routes = array("/(?P<hej>whatnow)/"=>array("thisismycontroller","Route"));
$Routing = new Route($routes);
echo "<pre>";
$controller = $Routing->handleRoute();
var_dump($controller);
$func = new $controller["controller"][0];
var_dump($func);
$func->$controller["controller"][1]($controller["matches"]);

echo "</pre>";
*/