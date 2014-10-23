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
            RedirectHandler::routeTo("/");
        }
        /** @var QuizModel $quiz */
        /*$quiz = new QuizModel("Some name", "Desciption here", "all");
        $question = new QuestionModel();
        $answers = array(new AnswerModel("Answer 1",1),new AnswerModel("Answer 2",0),new AnswerModel("Answer 3",1),new AnswerModel("Answer 4",0));
        $question->addAnswers($answers);
        $quiz->saveQuiz(array($question));*/


        /*
         * Hand of the controlling to the cud controller if we find out that the user wanted to edit, add or delete a quiz
         */
        $didCUDMatch = preg_match("/^\/(edit|add|delete)/", $route, $CUDmatches);
        if ($didCUDMatch) {
            $cudController = new QuizCUDController();

            return $cudController->getHTML($route, false);
        }
        //If we are in /quiz/(?P<quizid>) we should go ahead and display the page of the quiz
        //Or maybe display the results if we got something posted to us
        $didMatchQuiz = preg_match("/^\/quiz\/(?P<quizid>\d+)/", $route, $matches);
        if ($matches) {
            $quiz = $this->quizList->getQuizById($matches["quizid"]);
            var_dump($quiz);

            if ($this->view->getRequestMethod() === "POST") {
                $result = $quiz->validateAnswers($_POST);
                if ($result["allCorrect"] === true) {
                    echo "Oh my lordy, you guessed all correct!";
                }

                return $this->view->getResultsPage($result, $quiz);
            }
            if ($didMatchQuiz) {
                return $this->view->getQuizPage($quiz);
            }
        }

        return $this->view->getQuizesPage();
    }
}