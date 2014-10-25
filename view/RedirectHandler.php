<?php
defined("__ROOT__") or die("Noh!");

/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-08
 * Time: 11:19
 */
class RedirectHandler {
    static public function routeTo($route){
        if (strpos($route, View::$rootBase) !== 0) {
            header("Location: " . View::$rootBase . $route);
        } else {
            header("Location: " . $route);
        }
        exit;
    }
}