<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:31
 */
require_once("View.php");
class RegisterView extends View {

    /*
     * @todo: Change this to a proper function
     */
    public function getUsername(){
        return $_POST["username"];
    }
    /*
     * @todo: Change this to a proper function
     */
    public function getPassword(){
        return $_POST["password"];
    }

    /*
     * @todo: Change this to a proper function
     */
    public function getRepeatedPassword(){
        return $_POST["repeatedpassword"];
    }

    public function getEmail()
    {
        return $_POST["email"];
    }

    public function getRegisterPage($message = "", $errors = null)
    {
        $errorResult = "";
        if ($errors) {
            foreach ($errors as $error) {
                $errorResult .= $error;
            }
        }
        $html = '<p>This is the Register page</p>
                    ' . $message . $errorResult . '
                    <form method="post">
                        <input type="text" name="username" placeholder="Username">
                        <input type="password" name="password" placeholder="Password">
                        <input type="password" name="repeatedpassword" placeholder="Repeat password">
                        <input type="email" name="email" placeholder="Email">
                        <input type="submit" value="Register">
                    </form>
        ';
        return $html;
    }

}