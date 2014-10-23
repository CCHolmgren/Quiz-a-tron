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
                       QuizView::$removeMethodName .
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
        if ($matches["method"] === QuizView::$editMethodName) {
            if ($requestMethod === "POST") {
                if (isset($matches["answerid"])) {
                    $data = $this->view->getEditData();
                    $answer = $quiz->getQuestionById($matches["questionid"])->getAnswerById($matches["answerid"]);
                    $answer->setAnswertext($data["answertext"]);
                    $answer->setIscorrect($data["iscorrect"] == "on" ? 1 : 0);
                    $answer->updateAnswer();
                    RedirectHandler::routeTo($this->view->rootAndMethod($matches["method"]) . "/{$matches[self::$quizid]}/{$matches["questionid"]}");
                    echo "Editing an answer";
                } else {
                    if (isset($matches["questionid"])) {
                        $data = $this->view->getEditData();
                        /** @var QuestionModel $question */
                        $question = $quiz->getQuestionById($matches["questionid"]);
                        $question->setQuestiontext($data["questiontext"]);
                        $question->updateQuestion();
                        RedirectHandler::routeTo($this->view->rootAndMethod($matches["method"]) . "/{$matches["quizid"]}");
                    } else {
                        if (isset($matches["quizid"])) {
                            $data = $this->view->getEditData();
                            $quiz->description = $data["quiztext"];
                            $quiz->updateQuiz();
                            RedirectHandler::routeTo($this->view->rootAndMethod($matches["method"]) . "/");
                            echo "Editing a quiz";
                        }
                    }
                }
            } else {
                if (isset($matches["answerid"])) {
                    /** @var QuestionModel $question */
                    /** @var AnswerModel $answer */

                    $question = $quiz->getQuestionById($matches["questionid"]);
                    $answer = $question->getAnswerById($matches["answerid"]);

                    return $this->view->getEditAnswerPage($quiz, $question, $answer);
                }
                if (isset($matches["questionid"])) {
                    $question = $quiz->getQuestionById($matches["questionid"]);

                    return $this->view->getQuestionPage($quiz, $question);
                }
                if ($quiz !== false) {
                    return $this->view->getEditQuizPage($quiz);
                }

                return $this->view->getQuizesPage(true);
            }
        } else {
            if ($matches["method"] === QuizView::$removeMethodName && !isset($matches["questionid"])) {
                if ($requestMethod === "POST") {
                    if ($this->view->getTotallySure() === "true") {
                        $quiz->removeQuiz();
                        RedirectHandler::routeTo($this->view->rootAndMethod(QuizView::$removeMethodName) . "/");
                    }
                } else {
                    if ($quiz !== false) {
                        return $this->view->getRemoveQuizPage($quiz);
                    }

                    return $this->view->getQuizesPage(true);
                }
            } else {
                if ($matches["method"] === QuizView::$addMethodName) {
                    if ($requestMethod === "POST") {
                        //We want to add an answer
                        if (isset($matches["questionid"])) {
                            $data = $this->view->getAddData();
                            $answer = new AnswerModel();
                            $answer->setQuestionid($matches["questionid"]);
                            $answer->setAnswertext($data["answertext"]);
                            $answer->setIscorrect($data["iscorrect"] == "on" ? 1 : 0);
                            $answer->saveAnswer();
                            RedirectHandler::routeTo($this->view->rootAndMethod($matches["method"]) . "/{$matches["quizid"]}/{$matches["questionid"]}");
                            echo "Adding an answer";
                        } //We want to add a question
                        else {
                            if (isset($matches["quizid"])) {
                                $data = $this->view->getAddData();
                                /** @var QuestionModel $question */
                                $question = new QuestionModel();
                                $question->setQuestiontext($data["questiontext"]);
                                $question->setQuizid($matches["quizid"]);
                                $question->addQuestion();
                                RedirectHandler::routeTo($this->view->rootAndMethod($matches["method"]) . "/{$matches["quizid"]}");
                            } //We want to add a quiz
                            else {
                                $data = $this->view->getAddData();
                                $quiz = new QuizModel();
                                $quiz->setDescription($data["description"]);
                                $quiz->setCreator(UserModel::getCurrentUser()->getId());
                                $quiz->setName($data["name"]);
                                $quiz->setOpento($data["opento"]);
                                $quiz->addQuiz();
                                //$quiz->updateQuiz();
                                RedirectHandler::routeTo($this->view->rootAndMethod(QuizView::$editMethodName) . "/");
                            }
                        }
                    } else {
                        if (isset($matches["questionid"])) {
                            $quiz = $this->quizList->getQuizById($matches["quizid"]);
                            $question = $quiz->getQuestionById($matches["questionid"]);

                            return $this->view->getAddAnswerPage($question);
                        }
                        if (isset($matches["quizid"])) {
                            $quiz = $this->quizList->getQuizById($matches["quizid"]);

                            return $this->view->getAddQuestionPage($quiz);
                        }

                        return $this->view->getAddQuizPage();
                    }
                }
            }
        }
        return $this->view->getCUDPage();

    }
}