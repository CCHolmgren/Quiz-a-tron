<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 09:12
 */
require_once("Controller.php");
require_once("QuizCUDController.php");
require_once(__ROOT__ . "view/QuizView.php");

class QuizController extends Controller {
    private $view;

    public function __construct() {
        $this->view = new QuizView();
        $this->quizList = new QuizList();
        parent::__construct();
    }

    protected function __getHTML($route) {
        if (!UserModel::getCurrentUser()->isLoggedIn()) {
            $this->view->messages->saveMessage("You can only look at that page if you are logged in!");
            RedirectHandler::routeTo("");
        }
        /** @var QuizModel $quiz */

        //Hand of the controlling to the cud controller if we find out that the user wanted to edit, add or delete a quiz

        $didCUDMatch =
            preg_match("/^\/(" .
                       QuizView::$editMethodName . "|" .
                       QuizView::$addMethodName . "|" .
                       QuizView::$removeMethodName . "|" .
                       QuizView::$resultMethodName . ")/",
                       $route, $CUDmatches);
        //If we get a match then the user wants to do editing and as such we hand of the controlling to the CUDController
        if ($didCUDMatch) {
            $cudController = new QuizCUDController();

            return $cudController->getHTML($route, false);
        }

        //If we are in /quiz/(?P<quizid>) we should go ahead and display the page of the quiz
        //Or maybe display the results if we got something posted to us
        $didMatchQuiz = preg_match("/^\/quiz\/(?P<quizid>\d+)/", $route, $matches);
        if ($matches) {
            $quiz = $this->quizList->getQuizById($matches["quizid"]);

            if ($this->view->getRequestMethod() === "POST") {
                $result = $quiz->validateAnswers($_POST);
                RedirectHandler::routeTo("quizes/" . QuizView::$resultMethodName . "/" . $quiz->getId());
                //return $this->view->getResultsPage($result, $quiz);
            }
            if ($didMatchQuiz) {
                return $this->view->getQuizPage($quiz);
            }
        }

        return $this->view->getQuizesPage();
    }
}