<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:32
 */
class LoginView extends View{
    public function getLoginPage(){
        $html = "<!doctype html>
                <html>
                <head>
                    <title>Login page</title>
                    <meta charset='utf-8'>
                </head>
                <body>
                    <p>This is the loginpage</p>
                    <form method='post'>
                        <input type='submit' value='Login'>
                    </form>
                </body>
                <html>
        ";
        return $html;
    }
}