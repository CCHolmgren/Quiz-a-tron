<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:32
 */
require_once("RedirectHandler.php");
require_once("Controller.php");
require_once(__ROOT__."view/RegisterView.php");
require_once(__ROOT__."model/UserModel.php");

class RegisterController extends Controller {
    private $registerView;
    private $userModel;

    public function __construct($view = null, $model = null){
        $this->registerView = $view === null ? new RegisterView() : $view;
        $this->userModel = $model === null ? new UserModel() : $model;
    }
    public function getHTML(){
        if($this->userModel->isLoggedIn()){
            RedirectHandler::routeTo("?/");
        } else {
            if($this->registerView->getRequestMethod() === "POST"){
                if($this->userModel->validateInput()){
                    RedirectHandler::routeTo("?/login");
                }
                return "You posted something and it failed";
            }
            return $this->registerView->getRegisterPage();
        }
    }
}