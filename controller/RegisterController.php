<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:32
 */
require_once("Controller.php");
require_once(__ROOT__ . "view/RedirectHandler.php");
require_once(__ROOT__ . "view/RegisterView.php");
require_once(__ROOT__ . "model/UserModel.php");

class RegisterController extends Controller {
    private $registerView;

    public function __construct($view = null, $model = null) {
        $this->registerView = $view === null ? new RegisterView() : $view;
        parent::__construct();
    }

    protected function __getHTML($route) {
        $message = "";
        $errors = "";

        if ($this->user->isLoggedIn()) {
            RedirectHandler::routeTo("");
        } else {
            if ($this->registerView->getRequestMethod() === "POST") {
                $errors = $this->user->validateInput($this->registerView->getRegisterData());

                if ($errors === true) {
                    $tempUser = new UserModel();
                    $username = $this->registerView->getUsername();
                    $password = $this->registerView->getPassword();
                    $email = $this->registerView->getEmail();

                    try {
                        //We get another user out of the registerUser function
                        //Might as well capture it.
                        $tempUser = $tempUser->registerUser($username, $password, $email);
                        $this->registerView->messages->saveMessage("You registered. Now you may login.");
                        RedirectHandler::routeTo("login/");
                    } catch (Exception $e) {
                        $message = $e->getMessage();
                    }
                }
            }

            return $this->registerView->getRegisterPage($message, $errors);
        }
    }
}