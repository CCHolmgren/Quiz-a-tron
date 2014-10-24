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
            <a class="navbar-brand" href="/project/">QUIZ-A-TRON</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <!--<li>
                    <a href="/project/">Home</a>
                </li>-->
                <li>

                    <div class="btn-group">
                    <a href="/project/quizes" class="btn btn-default navbar-btn">Quizes</a>
                        <button type="button" class="btn btn-default dropdown-toggle navbar-btn" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/project/quizes/add/">Add a quiz</a></li>
                            <li><a href="/project/quizes/edit/">Edit quizes</a></li>
                        </ul>
                    </div>
                </li>

            </ul>
            <ul class="nav navbar-nav navbar-right">';
        if (!UserModel::isLoggedIn()) {
            $html .= '
                <li>
                    <a href="/project/login">Login</a>
                </li>';
            $html .= '
                <li>
                    <a href="/project/register">Register</a>
                </li>';
        } else {
            $username = UserModel::getCurrentUser()->getUsername();
            $userid = UserModel::getCurrentUser()->getId();
            $html .= '
            <li>
                <a href="/project/user/' . $username . '"> ' . $username . '</a>
            </li>
            <li>
                <a>User id: ' . $userid . '</a>
            </li>
            ';
            $html .= '
                <li>
                    <a href="/project/logout">Logout</a>
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