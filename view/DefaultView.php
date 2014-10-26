<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-08
 * Time: 11:17
 */
require_once("View.php");
class DefaultView extends View {
    public function __construct(){
        $this->quizview = new QuizView();
        parent::__construct();
    }
    public function getDefaultPage(){
        $html = '';
        if ($messages = $this->messages->getMessages()) {
            foreach ($messages as $message) {
                $html .= $message;
            }
        }
        if (UserModel::isLoggedIn()) {
            $html .= '<h2>Hello there ' . UserModel::getCurrentUser()->getUsername() . '. You are logged in!</h2>';
            $html .= 'You are logged in as ' . UserModel::getCurrentUser()->getUsername();
        }
            $html .= '
                <p>Hello there from default view</p>';
        $html .= "<p>You might want to go do some quizes, since that's what this page is for?</p>";
        $html .= "<p class='lead'>Here are the top 5 quizes of all time:</p>";
        $html .= $this->quizview->getQuizesPage(false, QuizList::getPopular(), false, true);
        $html .= "<div style='width:50%;margin: 0 auto; min-width:200px;'>";
        $html .= $this->quizview->getMostDone(QuizList::getMostDone());
        $html .= "</div>";

        //$html .= json_encode(QuizList::getMostDone());
        return $html;
    }
}