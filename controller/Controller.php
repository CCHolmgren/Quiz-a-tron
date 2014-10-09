<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:30
 */
class Controller{
    protected $model;
    public function __construct(){
        $this->model = UserModel::getCurrentUser();
    }
    public function getHTML($route){

    }
}