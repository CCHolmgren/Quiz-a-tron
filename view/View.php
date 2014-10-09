<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:30
 */
class View{
    public function __construct(){

    }
    public function getRequestMethod(){
        return $_SERVER["REQUEST_METHOD"];
    }
}