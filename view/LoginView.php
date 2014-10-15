<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:32
 */
require_once("View.php");
class LoginView extends View{
    public function getHead()
    {
        $head = "<title>Login page</title>";
        return $head;
    }
    public function getLoginPage(){
        $html = "<p>This is the loginpage</p>
                    <form method='post'>
                        <input type='text' name='username' placeholder='Username'>
                        <input type='password' name='password' placeholder='Password'>
                        <input type='submit' value='Login'>
                    </form>
        ";
        return $html;
    }
}