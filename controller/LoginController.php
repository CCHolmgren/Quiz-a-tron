<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:32
 */
require_once(__ROOT__."view/LoginView.php");
require_once(__ROOT__."model/UserModel.php");

class LoginController extends Controller {
    private $view;

    public function __construct($view = null, $model = null){
        $this->loginView = $view === null ? new LoginView() : $view;
        parent::__construct();
    }
    public function getHTML($route){
        if($this->model->isLoggedIn()){
            RedirectHandler::routeTo("?/");
        } else {
            if($this->loginView->getRequestMethod() === "POST"){
                if($this->model->validateLogin($this->loginView->getUserData())){
                    RedirectHandler::routeTo("?/");
                }
                return "You tried to login and it failed";
            }
            return $this->loginView->getLoginPage();
        }
    }
}