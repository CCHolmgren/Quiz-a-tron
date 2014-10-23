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
            <a class="navbar-brand" href="#">QUIZ-A-TRON</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="?/">Home</a>
                </li>
                <li>
                    <a href="?/quizes">Quizes</a>
                </li>

            </ul>
            <ul class="nav navbar-nav navbar-right">';
        if (!UserModel::isLoggedIn()) {
            $html .= '
                <li>
                    <a href="?/login">Login</a>
                </li>';
            $html .= '
                <li>
                    <a href="?/register">Register</a>
                </li>';
        } else {
            $username = UserModel::getCurrentUser()->getUsername();
            $userid = UserModel::getCurrentUser()->getId();
            $html .= '
            <li>
                <a href="?/user/' . $username . '"> ' . $username . '</a>
            </li>
            <li>
                <a>User id: ' . $userid . '</a>
            </li>
            ';
            $html .= '
                <li>
                    <a href="?/logout">Logout</a>
                </li>';
        }
        $html .= '
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
';

        /*        $html = "<ul>
                            <li>
                                <a href='?/'>Home</a>
                            </li>
                            <li>
                                <a href='?/login'>Login</a>
                            </li>
                            <li>
                                <a href='?/logout'>Logout</a>
                            </li>
                            <li>
                                <a href='?/register'>Register</a>
                            </li>
                            <li>
                                <a href='?/quizes'>Quizes</a>
                            </li>
                        </ul>";*/
        return $html;
    }
}