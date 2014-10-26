<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 12:43
 */
require_once("View.php");
class LogoutView extends View{
    public function getLogoutPage(){
        $html = "";
        if ($messages = $this->messages->getMessages()) {
            foreach ($messages as $message) {
                $html .= $message;
            }
        }
        $html .= "<p>This is the logout page</p>
                    <form method='post'>
                        <input type='submit' value='Logout' class='btn btn-primary'>
                    </form>
        ";
        return $html;
    }
}