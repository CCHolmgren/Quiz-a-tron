<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 12:32
 */
require_once(__ROOT__ . "view/RedirectHandler.php");
require_once(__ROOT__ . "model/UserModel.php");
require_once(__ROOT__ . "view/LogoutView.php");

class LogoutController extends Controller {

    public function __construct($view = null, $model = null) {
        $this->view = $view === null ? new LogoutView() : $view;
        parent::__construct();
    }

    protected function __getHTML($route) {
        if ($this->user->isLoggedIn()) {
            if ($this->view->getRequestMethod() === "POST") {
                $this->user->logout();
                $this->view->messages->saveMessage("You logged out!");
                RedirectHandler::routeTo($this->view->rootBase);
            }

            return $this->view->getLogoutPage();
        }
        $this->view->messages->saveMessage("You are already logged out!");
        RedirectHandler::routeTo("/");

        return "";
    }
}
