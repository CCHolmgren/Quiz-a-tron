<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-08
 * Time: 11:17
 */
require_once("View.php");
class DefaultView extends View {
    public function __construct(){
    }
    public function getDefaultPage(){
        $html = '';
        if(isset($_SESSION["loggedin"])) {
            $html .= '<p>Hello there. You are logged in!</p>';
        }
            $html .= '
                <p>Hello there from default view</p>';
        return $html;
    }
}