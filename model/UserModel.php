<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-08
 * Time: 11:51
 */
require_once("Model.php");
class UserModel extends Model {
    private $errors;
    private $username;
    private $password;

    public function __construct(){}

    /*
     * @todo: Implement this function properly
     */
    public function isLoggedIn(){
        return false;
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
        return true;
    }
    public function getErrors(){
        return $this->errors;
    }
}