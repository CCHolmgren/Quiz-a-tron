<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 12:16
 */

require_once("RedirectHandler.php");
require_once("Controller.php");
require_once(__ROOT__."view/DefaultView.php");
require_once(__ROOT__."model/UserModel.php");


/*
 * This is the DefaultController, maybe change the way that
 * the routing works and that importing will happen based on the RoutingDirectives?
 */
class DefaultController extends Controller{
    private $defaultView;

    public function __construct($view = null, $model = null){
        $this->defaultView = $view === null ? new DefaultView() : $view;
        parent::__construct();
    }

    public function getHTML($route){
        if($_SERVER["QUERY_STRING"] !== "/")
            RedirectHandler::routeTo("?/");

        return $this->defaultView->getDefaultPage();
    }
}