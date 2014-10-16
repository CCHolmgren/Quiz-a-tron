<?php
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
        $html = "<ul>
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
                </ul>";
        return $html;
    }
}