<?php
/*
 * This page will be the entry point and the only page that will handle the app.
 * As such, it will have a switchstatement or something that will handle the routing and then hand away the control to
 * the controllers that handles the different functions (such as login, register, doing quizes, making quizes and such
 */

/*
 * Define the __ROOT__ global so that it won't be a real pain to move this to a webserver later
 */
define("__ROOT__", realpath("") . "/");//"C:/Users/Chrille/Desktop/PHP-projekt/");
/*
 * All requires that are needed by this page
 * We must do the requires first since we are storing the user in the session and as such it will get unserialized
 * And if it doesn't know what a UserModel is before the session is created, then it will break horribly
 */
require_once(__ROOT__ . "controller/Routing.php");
require_once(__ROOT__ . "controller/RegisterController.php");
require_once(__ROOT__ . "controller/LoginController.php");
require_once(__ROOT__ . "controller/LogoutController.php");
require_once(__ROOT__ . "controller/QuizController.php");
require_once(__ROOT__ . "controller/DefaultController.php");
require_once(__ROOT__ . "controller/RoutingDirective.php");
require_once(__ROOT__ . "controller/UserController.php");
require_once(__ROOT__ . "model/Messages.php");
require_once("Settings.php");

session_start();

class NotImplementedException extends Exception {
}

/*
 * Define the RoutingDirectives so that the routing knows to what controller to send the controls to
 */

$routes = new RouteList(new RoutingDirective("/PHP-project\/register/", "RegisterController", "getHTML", "register"),
                        new RoutingDirective("/PHP-project\/login/", "LoginController", "getHTML", "login"),
                        new RoutingDirective("/PHP-project\/logout/", "LogoutController", "getHTML", "logout"),
                new RoutingDirective("/PHP-project\/quizes\/?/", "QuizController", "getHTML", "quizes"),
                new RoutingDirective("/PHP-project\/user\//", "UserController", "getHTML", "user"),
                new RoutingDirective("//", "DefaultController", "getHTML", "default"));

// The Route is a class that handles routes, at this moment it only does handleRoute, but maybe more in the future
$routing = new Route($routes);

// PHP is ugly and can't handle several argument returns, not as of 5.5 but from 5.6
// We use 5.5 here
$routingmatch = $routing->handleRoute();
$rd = $routingmatch->getRoutingDirective();
$matches = $routingmatch->getMatches();

//$controller, $matches = $routing->handleRoute();

/*
 * This is an ugly way to handle stuff, but it works, doesn't it?
 */
$controller = new $rd->controllername();
$fn = $rd->functionname;
echo $controller->$fn(preg_replace($rd->regex, "", View::getRequestURI()));