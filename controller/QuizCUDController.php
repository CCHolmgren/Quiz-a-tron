<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-16
 * Time: 12:09
 */
require_once("Controller.php");

class QuizCUDController extends Controller {
    public static $quizid = "quizid";
    private $view;

    public function __construct() {
        $this->view = new QuizView();
        $this->quizList = new QuizList();
        parent::__construct();
    }

    /**
     * @param $route
     * @return string
     */
    protected function __getHTML($route) {
        $didMatch =
            preg_match("/^\/(?P<method>" .
                       QuizView::$editMethodName . "|" .
                       QuizView::$addMethodName . "|" .
                       QuizView::$removeMethodName . "|" .
                       QuizView::$resultMethodName .
                       ")\/?(?P<" .
                       self::$quizid .
                       ">\d+)?\/?(?P<questionid>\d+)?\/?(?P<answerid>\d+)?/",
                       $route, $matches);

        /** @var String $requestMethod */
        $requestMethod = $this->view->getRequestMethod();
        /** @var QuizModel $quiz */
        $quiz = false;

        if (isset($matches[self::$quizid])) {
            $quiz = $this->quizList->getQuizById($matches[self::$quizid]);
        }

        return $this->handleRoute($quiz, $requestMethod, $matches);


    }

    private function handleRoute($quiz, $requestMethod, $matches) {
        if ($matches["method"] === QuizView::$editMethodName) {
            return $this->handleEdit($quiz, $requestMethod, $matches);
        } else if ($matches["method"] === QuizView::$removeMethodName) {
            return $this->handleRemove($quiz, $requestMethod, $matches);
        } else if ($matches["method"] === QuizView::$addMethodName) {
            return $this->handleAdd($quiz, $requestMethod, $matches);
        } else if ($matches["method"] === QuizView::$resultMethodName) {
            return $this->handleResult($quiz, $requestMethod, $matches);
        } else {
            return $this->view->getCUDPage();
        }
    }

    private
    function handleEdit($quiz, $requestMethod, $matches) {
        //Request method was POST
        if ($requestMethod === "POST") {
            if (isset($matches["answerid"])) {
                $this->editAnswer($quiz, $matches);
                RedirectHandler::routeTo($this->view->rootAndMethod($matches["method"]) . "/{$matches[self::$quizid]}/{$matches["questionid"]}");
            } else if (isset($matches["questionid"])) {
                $this->editQuestion($quiz, $matches);
                RedirectHandler::routeTo($this->view->rootAndMethod($matches["method"]) . "/{$matches["quizid"]}");
            } else if (isset($matches["quizid"])) {
                $this->editQuiz($quiz);
                RedirectHandler::routeTo($this->view->rootAndMethod($matches["method"]) . "/");
            }
            //Request method was GET
        } else {
            if (isset($matches["answerid"])) {
                /** @var QuestionModel $question */
                /** @var AnswerModel $answer */
                /** @var QuizModel $quiz */
                $question = $quiz->getQuestionById($matches["questionid"]);
                $answer = $question->getAnswerById($matches["answerid"]);

                return $this->view->getEditAnswerPage($quiz, $question, $answer);
            } else if (isset($matches["questionid"])) {
                $question = $quiz->getQuestionById($matches["questionid"]);

                return $this->view->getEditQuestionPage($quiz, $question);
            } else if ($quiz !== false) {
                return $this->view->getEditQuizPage($quiz);
            } else {
                return $this->view->getQuizesPage(true);
            }
        }
    }

    public
    function editAnswer($quiz, $matches) {
        $data = $this->view->getEditData();
        $answer = $quiz->getQuestionById($matches["questionid"])->getAnswerById($matches["answerid"]);
        $answer->setAnswertext($data["answertext"]);
        $answer->setIscorrect($data["iscorrect"] == "on" ? 1 : 0);
        $answer->updateAnswer();
    }

    public
    function editQuestion($quiz, $matches) {
        $data = $this->view->getEditData();
        /** @var QuestionModel $question */
        $question = $quiz->getQuestionById($matches["questionid"]);
        $question->setQuestiontext($data["questiontext"]);
        $question->updateQuestion();
    }

    public
    function editQuiz(QuizModel $quiz) {
        $data = $this->view->getEditData();
        $quiz->setName($data["name"]);
        $quiz->setOpento($data["opento"]);
        $quiz->setDescription($data["description"]);
        $quiz->updateQuiz();
    }

    /**
     * @todo: Implement the removal of things for real
     * @param $quiz
     * @param $requestMethod
     * @param $matches
     * @return string
     */
    private
    function handleRemove($quiz, $requestMethod, $matches) {
        //Request method was POST
        if ($requestMethod === "POST") {
            if ($this->view->getTotallySure() === "true") {
                if (isset($matches["questionid"])) {
                    /** @var QuizModel $quiz */
                    $question = $quiz->getQuestionById($matches["questionid"]);
                    $question->removeQuestion();
                    $this->view->messages->saveMessage("The question was removed");
                    RedirectHandler::routeTo($this->view->rootAndMethod(QuizView::$editMethodName) . "/" . $quiz->getId());
                } else if (isset($matches["answerid"])) {
                    $question = $quiz->getQuestionById($matches["questionid"]);
                    $answer = $question->getAnswerById($matches["answerid"]);
                    $answer->removeAnswer();
                    $this->view->messages->saveMessage("The answer was removed");
                    RedirectHandler::routeTo($this->view->rootAndMethod(QuizView::$editMethodName) . "/" . $quiz->getId() . "/" . $question->getId());
                } else if (isset($matches["quizid"])) {
                    $quiz = $this->quizList->getQuizById($matches["quizid"]);
                    $quiz->removeQuiz();
                    $this->view->messages->saveMessage("The quiz was removed");
                    RedirectHandler::routeTo($this->view->rootAndMethod(QuizView::$editMethodName) . "/");
                }


            }
            //Request method was GET
        } else {
            if ($quiz !== false) {
                return $this->view->getRemoveQuizPage($quiz);
            } else {
                return $this->view->getQuizesPage(true);
            }
        }
    }

    private
    function handleAdd($quiz, $requestMethod, $matches) {
        if ($requestMethod === "POST") {
            if (isset($matches["questionid"])) {
                $this->createAnswer($matches);
                RedirectHandler::routeTo($this->view->rootAndMethod($matches["method"]) . "/{$matches["quizid"]}/{$matches["questionid"]}");
            } else if (isset($matches["quizid"])) {
                $this->createQuestion($matches);
                RedirectHandler::routeTo($this->view->rootAndMethod($matches["method"]) . "/{$matches["quizid"]}");
            } else {
                $this->createQuiz();
                RedirectHandler::routeTo($this->view->rootAndMethod(QuizView::$editMethodName) . "/");
            }
            //Request method was GET
        } else {
            if (isset($matches["questionid"])) {
                $quiz = $this->quizList->getQuizById($matches["quizid"]);
                $question = $quiz->getQuestionById($matches["questionid"]);

                return $this->view->getAddAnswerPage($quiz, $question);
            } else if (isset($matches["quizid"])) {
                $quiz = $this->quizList->getQuizById($matches["quizid"]);

                return $this->view->getAddQuestionPage($quiz);
            } else {
                return $this->view->getAddQuizPage();
            }
        }
    }

    public
    function createAnswer($matches) {
        $data = $this->view->getAddData();
        $answer = new AnswerModel();
        $answer->setQuestionid($matches["questionid"]);
        $answer->setAnswertext($data["answertext"]);
        $answer->setIscorrect($data["iscorrect"] == "on" ? 1 : 0);
        $answer->saveAnswer();
    }

    public
    function createQuestion($matches) {
        $data = $this->view->getAddData();
        /** @var QuestionModel $question */
        $question = new QuestionModel();
        $question->setQuestiontext($data["questiontext"]);
        $question->setQuizid($matches["quizid"]);
        $question->addQuestion();
    }

    public
    function createQuiz() {
        $data = $this->view->getAddData();
        $quiz = new QuizModel();
        $quiz->setDescription($data["description"]);
        $quiz->setCreator(UserModel::getCurrentUser()->getId());
        $quiz->setName($data["name"]);
        $quiz->setOpento($data["opento"]);
        $quiz->addQuiz();
    }

    private
    function handleResult($quiz, $requestMethod, $matches) {
        return $this->view->getResultPage($quiz, UserModel::getCurrentUser());
    }
}