<?php
defined("__ROOT__") or die("Noh!");
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

    public function getLoginPage($message) {
        $html = "";
        if ($messages = $this->messages->getMessages()) {
            foreach ($messages as $message) {
                $html .= $message;
            }
        }
        $html .= "
                    <form method='post' role='form' class='form-horizontal'>
                        <div class='form-group'>
                            <label for='username' class='col-sm-2 control-label'>Username</label>
                            <div class='col-sm-9'>
                                <input type='text' name='username' placeholder='Username' class='form-control'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for='username' class='col-sm-2 control-label'>Password</label>
                            <div class='col-sm-9'>
                                <input type='password' name='password' placeholder='Password' class='form-control'></div>
                            </div>
                        <div class='form-group'>
                            <div class='col-sm-offset-2 col-sm-10'>
                                <input type='submit' class='btn btn-default' value='Sign in'>
                            </div>
                        </div>
                    </form>

        ";
        return $html;
    }
}