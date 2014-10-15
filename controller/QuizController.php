<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 09:12
 */
require_once("Controller.php");
require_once(__ROOT__ . "view/QuizView.php");

class QuizController extends Controller {
    private $view;

    public function __construct(){
        $this->view = new QuizView();
        $this->quizList = new QuizList();
        parent::__construct();
    }

    protected function __getHTML($route)
    {
        echo $route;
        var_dump(UserModel::getCurrentUser());
        var_dump((new QuizModel())->getDoneQuizes(UserModel::getCurrentUser()->getId()));

        $didMatchQuiz = preg_match("/^\/quiz\/(?P<quizid>\d+)/", $route, $matches);
        if ($matches) {
            $quiz = $this->quizList->getQuizById($matches["quizid"]);

            if ($this->view->getRequestMethod() === "POST") {
                $result = $quiz->validateAnswers($_POST);
                return $this->view->getResultsPage($result, $quiz);
            }
            if ($didMatchQuiz) {
                return $this->view->getQuizPage($quiz);
            }
        }
        return $this->view->getQuizesPage();
    }
}