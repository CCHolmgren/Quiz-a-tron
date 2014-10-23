<?php
defined("__ROOT__") or die("Noh!");
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
require_once(__ROOT__ . "model/UserModel.php");
require_once(__ROOT__ . "view/NavigationView.php");

class Controller {
    protected $model;

    public function __construct() {
        $this->model = UserModel::getCurrentUser();
        $this->navigationView = new NavigationView();
    }

    /**
     * @param string $route The route that should be passed on to the __getHTML function
     * @param bool $splice If true use spliceBaseHTML and give out the HTML, if false just return body
     * This way we can use a controller inside another controller without any problem, as long as we remember to set
     * splice to false
     * @return string Either a HTML document or the body of a html document without the navigation
     */
    final public function getHTML($route = "", $splice = true) {
        $head = $this->__getHead();
        $body = $this->__getHTML($route);
        $navigation = $this->navigationView->getNavigation();
        if ($splice) {
            return HTMLHelper::spliceBaseHTML($head, $navigation, $body);
        }

        return $body;
    }

    /*
     * This is the head that should get spliced into the BaseHTML
     * Overload this function and return a string that contain the head
     */
    protected function __getHead() {
        return "<title>Whatnow</title>";
    }

    /*
     * This is the body that should get spliced into the BaseHTML
     * Overload this function and return a string that contain the body
     */
    protected function __getHTML($route) {
        return "Well this is odd. This should be implemented";
    }
}