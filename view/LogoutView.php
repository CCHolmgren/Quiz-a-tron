<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 12:43
 */
require_once("View.php");
class LogoutView extends View{
    public function getLogoutPage(){
        $html = "<!doctype html>
                <html>
                <head>
                    <title>Logout page</title>
                    <meta charset='utf-8'>
                </head>
                <body>
                    <p>This is the logout page</p>
                    <form method='post'>
                        <input type='submit' value='Logout'>
                    </form>
                </body>
                <html>
        ";
        return $html;
    }
}