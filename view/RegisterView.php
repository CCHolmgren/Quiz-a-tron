<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:31
 */
require_once("View.php");
class RegisterView extends View {
    private static $username = "username";
    private static $password = "password";
    private static $repeatedPassword = "repeatedpassword";
    private static $email = "email";

    /*
     * @todo: Change this to a proper function
     */
    public function getUsername(){
        return $_POST[self::$username];
    }
    /*
     * @todo: Change this to a proper function
     */
    public function getPassword(){
        return $_POST[self::$password];
    }

    /*
     * @todo: Change this to a proper function
     */
    public function getRepeatedPassword(){
        return $_POST[self::$repeatedPassword];
    }

    public function getEmail()
    {
        return $_POST[self::$email];
    }

    public function getRegisterPage($message = "", $errors = null)
    {
        $errorResult = "";
        if ($errors) {
            foreach ($errors as $error) {
                $errorResult .= $error;
            }
        }
        $html = "<p class='bg-info'>$message</p> <p class='bg-danger'>$errorResult</p>
                <form method='post' role='form' class='form-horizontal'>
                        <div class='form-group'>
                            <label for='" . self::$username . "' class='col-sm-2 control-label'>Username</label>
                            <div class='col-sm-9'>
                                <input type='text' name='" . self::$username . "' placeholder='Write a username' class='form-control'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for='" . self::$password . "' class='col-sm-2 control-label'>Password</label>
                            <div class='col-sm-9'>
                                <input type='password' name='" . self::$password . "' placeholder='Write a password' class='form-control'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for='" . self::$repeatedPassword . "' class='col-sm-2 control-label'>Repeat password</label>
                            <div class='col-sm-9'>
                                <input type='password' name='" . self::$repeatedPassword . "' placeholder='Repeat password' class='form-control'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for='" . self::$email . "' class='col-sm-2 control-label'>Email</label>
                            <div class='col-sm-9'>
                                <input type='email' name='" . self::$email . "' placeholder='Your email' class='form-control'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class='col-sm-offset-2 col-sm-10'>
                                <input type='submit' class='btn btn-primary' value='Register'>
                            </div>
                        </div>
                    </form>";
        return $html;
    }

}