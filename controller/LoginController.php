<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:32
 */
require_once(__ROOT__ . "view/LoginView.php");
require_once(__ROOT__ . "model/UserModel.php");

class LoginController extends Controller {
    private $view;

    public function __construct($view = null, $model = null) {
        $this->view = $view === null ? new LoginView() : $view;
        parent::__construct();
    }

    protected function __getHead() {
        return $this->view->getHead();
    }

    protected function __getHTML($route) {
        $message = "";
        $error = "";

        if ($this->user->isLoggedIn()) {
            RedirectHandler::routeTo("");
        } else {
            if ($this->view->getRequestMethod() === "POST") {
                if ($this->user->validateLogin($this->view->getLoginData())) {
                    $this->view->messages->saveMessage("You logged in!");
                    RedirectHandler::routeTo("");
                }
                $error = "Please check the username and password and try again.";
            }

            return $this->view->getLoginPage($message, $error);
        }
    }
}