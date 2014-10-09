<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:30
 */
require_once(__ROOT__ . "helpers/HTMLHelper.php");

/*
 * Being so small this class does a lot of nice things
 *
 * This is the base that all controllers should extend from
 * As such it provides a getHTML function that gives an entry point
 * That way you call
 * $derived->getHTML() and get your HTML returned which you then echo to the user
 *
 * For your usage there is __getHead and __getHTML that will be where you place your code
 */
class Controller{
    protected $model;
    public function __construct(){
        $this->model = UserModel::getCurrentUser();
    }

    final public function getHTML()
    {
        $head = $this->__getHead();
        $body = $this->__getHTML("");
        return HTMLHelper::spliceBaseHTML($head, $body);
    }

    /*
     * This is the head that should get spliced into the BaseHTML
     * Overload this function and return a string that contain the head
     */
    protected function __getHead()
    {
        return "";
    }

    /*
     * This is the body that should get spliced into the BaseHTML
     * Overload this function and return a string that contain the body
     */
    protected function __getHTML($route)
    {
        return "Well this is odd. This should be implemented";
    }
}