<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 12:16
 */

require_once(__ROOT__ . "view/RedirectHandler.php");
require_once("Controller.php");
require_once(__ROOT__ . "view/UserView.php");
require_once(__ROOT__ . "model/UserModel.php");


/*
 * This is the DefaultController, maybe change the way that
 * the routing works and that importing will happen based on the RoutingDirectives?
 */

class UserController extends Controller {
    private $userView;

    public function __construct($view = null, $model = null) {
        parent::__construct();
    }

    protected function __getHTML($route) {
        preg_match("/\/([a-zA-Z0-9]+)/", $route, $username);
        $this->userView = new UserView(UserModel::getUserByUsername($username[1]));

        return $this->userView->getUserPage();
    }
}