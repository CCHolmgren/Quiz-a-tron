<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-08
 * Time: 11:51
 */
require_once("Model.php");
/*
 * Represents a user, with all of its might (not right now)
 * Should contain everything that a user has, such as username, password, quizes done and quizes left
 * Everything that you would need to check and handle stuff for the user
 */

class UserModel extends Model {
    private $id;
    private $errors;
    private $username;
    private $password;
    private $quizes;

    public function __construct(){
    }
    public function registerUser($username, $password, $repeatedpassword){
        //Get the connection and register the user
        //Return this which will be the new user, or throw an exception if it failed
        throw new NotImplementedException("registerUser is not implemented yet and as such will not have done anything");
    }
    /*
     * @todo: Implement this function properly
     */
    public function isLoggedIn(){
        return isset($_SESSION["loggedin"]);
    }
    /*
     * @todo: Implement this function properly
     */
    public function validateInput(){
        return true;
    }
    /*
     * @todo: Implement this function properly
     */
    public function validateLogin(){
        session_regenerate_id(true);
        $_SESSION["loggedin"] = true;
        $_SESSION["user"] = $this;
        return true;
    }
    static public function getCurrentUser(){
        if(isset($_SESSION["user"]))
            return $_SESSION["user"];
        return new UserModel();
    }
    public function logout(){
        session_destroy();
        session_regenerate_id(true);
    }
    public function getErrors(){
        return $this->errors;
    }
}