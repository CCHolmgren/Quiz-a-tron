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
    private $model;

    public function __construct($view = null, $model = null){
        $this->loginView = $view === null ? new LoginView() : $view;
        $this->userModel = $model === null ? new UserModel() : $model;
    }
    public function getHTML(){
        if($this->userModel->isLoggedIn()){
            RedirectHandler::routeTo("?/");
        } else {
            if($this->loginView->getRequestMethod() === "POST"){
                if($this->userModel->validateLogin()){
                    RedirectHandler::routeTo("?/");
                }
                return "You tried to login and it failed";
            }
            return $this->loginView->getLoginPage();
        }
    }
}