<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:32
 */
require_once(__ROOT__ . "view/RedirectHandler.php");
require_once("Controller.php");
require_once(__ROOT__."view/RegisterView.php");
require_once(__ROOT__."model/UserModel.php");

class RegisterController extends Controller {
    private $registerView;

    public function __construct($view = null, $model = null){
        $this->registerView = $view === null ? new RegisterView() : $view;
        parent::__construct();
    }

    protected function __getHTML($route)
    {
        if($this->model->isLoggedIn()){
            RedirectHandler::routeTo("?/");
        } else {
            if($this->registerView->getRequestMethod() === "POST"){
                if($this->model->validateInput($this->registerView->getUserData())){
                    $tempUser = new UserModel();
                    $username = $this->registerView->getUsername();
                    $password = $this->registerView->getPassword();
                    $repeatedpassword = $this->registerView->getRepeatedPassword();

                    try{
                        $tempUser->registerUser($username, $password, $repeatedpassword);
                    }
                    catch(Exception $e){
                        throw $e;
                    }
                    RedirectHandler::routeTo("?/login");
                }
                return "You posted something and it failed";
            }
            return $this->registerView->getRegisterPage();
        }
    }
}