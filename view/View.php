<?php
defined("__ROOT__") or die("Noh!");

/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:30
 */
require_once(__ROOT__ . "vendors/Parsedown.php");
class View{
    public static $rootBase = "/PHP-project/";

    public function __construct(){
    }

    public static function getRequestMethod() {
        return $_SERVER["REQUEST_METHOD"];
    }

    public static function getQueryString()
    {
        return $_SERVER["QUERY_STRING"];
    }

    public static function getRequestURI() {
        return $_SERVER["REQUEST_URI"];
    }

    public function getHead() {
        $head = "<title>Default title</title>";
        return $head;
    }
    /*
     * This function should return the userdata that is needed to handle a registration
     * Such as username, password, repeated password, email and so on
     *
     * As of now the function only returns _POST since that contains everything we need, but
     * should change this later to only do what we need
     * Maybe even do some CSRF checking and such
     *
     * @todo: Do basic validation of data here, maybe even escape the data if needed
     */

    public function getRegisterData()
    {
        return array("username" => $_POST["username"], "password" => $_POST["password"], "repeatedpassword" => $_POST["repeatedpassword"], "email" => $_POST["email"]);
    }

    public function getLoginData()
    {
        return array("username" => $_POST["username"], "password" => $_POST["password"]);
    }
}