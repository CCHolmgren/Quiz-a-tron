<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-16
 * Time: 12:29
 */
require_once("View.php");

class NavigationView extends View
{
    public function getNavigation()
    {
        $html = '
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="' . $this->rootBase . '">QUIZ-A-TRON</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <!--<li>
                    <a href="/PHP-project/">Home</a>
                </li>-->
                <li>

                    <div class="btn-group">
                    <a href="' . $this->rootBase . 'quizes/" class="btn btn-default navbar-btn">Quizes</a>
                        <button type="button" class="btn btn-default dropdown-toggle navbar-btn" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="' . $this->rootBase . 'quizes/' . QuizView::ADD_METHOD_NAME . '/">Add a quiz</a></li>
                            <li><a href="' . $this->rootBase . 'quizes/' . QuizView::EDIT_METHOD_NAME . '/">Edit quizes</a></li>
                        </ul>
                    </div>
                </li>

            </ul>
            <ul class="nav navbar-nav navbar-right">';
        if (!UserModel::isLoggedIn()) {
            $html .= '
                <li>
                    <a href="' . $this->rootBase . 'login">Login</a>
                </li>';
            $html .= '
                <li>
                    <a href="' . $this->rootBase . 'register">Register</a>
                </li>';
        } else {
            $username = UserModel::getCurrentUser()->getUsername();
            $userid = UserModel::getCurrentUser()->getId();
            $html .= '
            <li>
                <a href="' . $this->rootBase . 'user/' . $username . '"> ' . $username . '</a>
            </li>
            <li>
                <a>User id: ' . $userid . '</a>
            </li>
            ';
            $html .= '
                <li>
                    <a href="' . $this->rootBase . 'logout">Logout</a>
                </li>';
        }
        $html .= '
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
';
        return $html;
    }
}