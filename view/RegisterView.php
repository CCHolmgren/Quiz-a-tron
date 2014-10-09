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
    public function getRegisterPage(){
        $html = '<!doctype html>
                <html>
                <head>
                    <title>Register page</title>
                    <meta charset="utf-8">
                </head>
                <body>
                    <p>This is the Register page</p>
                    <form method="post">
                        <input type="text" name="username">
                        <input type="password" name="password">
                        <input type="password" name="repeatedpassword">
                        <input type="submit" value="Register">
                    </form>
                </body>
                <html>
        ';
        return $html;
    }

}