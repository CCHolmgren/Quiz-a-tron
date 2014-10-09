<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 12:32
 */
require_once("RedirectHandler.php");
require_once(__ROOT__."model/UserModel.php");
require_once(__ROOT__."view/LogoutView.php");

class LogoutController extends Controller {

    public function __construct($view = null, $model = null){
        $this->view = $view === null ? new LogoutView() : $view;
        parent::__construct();
    }
    public function getHTML($route){
        if($this->model->isLoggedIn()){
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                $this->model->logout();
                RedirectHandler::routeTo("?/");
            }
            return $this->view->getLogoutPage();
        }
        RedirectHandler::routeTo("?/");
    }
}
