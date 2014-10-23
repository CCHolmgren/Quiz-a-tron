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
        $html = "<p class='bg-info'>$message</p> <p class='bg-danger'>$errorResult</p>
                <form method='post' role='form' class='form-horizontal'>
                        <div class='form-group'>
                            <label for='username' class='col-sm-2 control-label'>Username</label>
                            <div class='col-sm-9'>
                                <input type='text' name='username' placeholder='Write a username' class='form-control'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for='password' class='col-sm-2 control-label'>Password</label>
                            <div class='col-sm-9'>
                                <input type='password' name='password' placeholder='Write a password' class='form-control'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for='username' class='col-sm-2 control-label'>Repeat password</label>
                            <div class='col-sm-9'>
                                <input type='password' name='repeatedpassword' placeholder='Repeat password' class='form-control'>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for='username' class='col-sm-2 control-label'>Email</label>
                            <div class='col-sm-9'>
                                <input type='email' name='email' placeholder='Your email' class='form-control'>
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