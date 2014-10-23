<?php
defined("__ROOT__") or die("Noh!");
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
        if (UserModel::isLoggedIn()) {
            $html .= '<h2>Hello there. You are logged in!</h2>';
            $html .= 'You are logged in as ' . UserModel::getCurrentUser()->getUsername();
        }
            $html .= '
                <p>Hello there from default view</p>';
        return $html;
    }
}