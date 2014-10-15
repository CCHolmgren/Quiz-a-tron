<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:30
 */
class View{
    public function __construct(){

    }

    public function getHead()
    {
        $head = "<title>Default title</title>";
        return $head;
    }
    public function getRequestMethod(){
        return $_SERVER["REQUEST_METHOD"];
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