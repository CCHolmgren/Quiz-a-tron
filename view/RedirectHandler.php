<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-08
 * Time: 11:19
 */
class RedirectHandler {
    static public function routeTo($route){
        header("Location: ".$route);
        exit;
    }
}