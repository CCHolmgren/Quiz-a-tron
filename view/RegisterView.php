<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:31
 */
require_once("View.php");
class RegisterView extends View {
    public function getRegisterPage(){
        $html = "<!doctype html>
                <html>
                <head>
                    <title>Register page</title>
                    <meta charset='utf-8'>
                </head>
                <body>
                    <p>This is the Register page</p>
                    <form method='post'>
                        <input type='submit' value='Register'>
                    </form>
                </body>
                <html>
        ";
        return $html;
    }

}