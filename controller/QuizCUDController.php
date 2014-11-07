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
    private static $quizIdName = "quizid";
    private static $methodName = "method";
    private static $questionIdName = "questionid";
    private static $answerIdName = "answerid";
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
            preg_match("/^\/(?P<" . self::$methodName . ">" .
                       QuizView::EDIT_METHOD_NAME . "|" .
                       QuizView::ADD_METHOD_NAME . "|" .
                       QuizView::REMOVE_METHOD_NAME . "|" .
                       QuizView::RESULT_METHOD_NAME .
                       ")\/?(?P<" .
                       self::$quizIdName .
                       ">\d+)?\/?(?P<" .
                       self::$questionIdName . ">\d+)?\/?(?P<" .
                       self::$answerIdName . ">\d+)?/",
                       $route, $matches);

        /** @var String $requestMethod */
        $requestMethod = $this->view->getRequestMethod();
        /** @var QuizModel $quiz */

        if (isset($matches[self::$quizIdName])) {
            $quiz = $this->quizList->getQuizById($matches[self::$quizIdName]);
        } else {
            $quiz = false;
        }

        return $this->handleRoute($quiz, $requestMethod, $matches);


    }

    private function handleRoute($quiz, $requestMethod, $matches) {
        if ($matches[self::$methodName] === QuizView::EDIT_METHOD_NAME) {
            return $this->handleEdit($quiz, $requestMethod, $matches);
        } else if ($matches[self::$methodName] === QuizView::REMOVE_METHOD_NAME) {
            return $this->handleRemove($quiz, $requestMethod, $matches);
        } else if ($matches[self::$methodName] === QuizView::ADD_METHOD_NAME) {
            return $this->handleAdd($quiz, $requestMethod, $matches);
        } else if ($matches[self::$methodName] === QuizView::RESULT_METHOD_NAME) {
            return $this->handleResult($quiz, $requestMethod, $matches);
        } else {
            return $this->view->getCUDPage();
        }
    }

    private
    function handleEdit($quiz, $requestMethod, $matches) {
        //Request method was POST
        if ($requestMethod === "POST") {
            if ($quiz !== false && UserModel::getCurrentUser()->getId() === $quiz->getCreator()) {

                if (isset($matches[self::$answerIdName])) {
                    $this->editAnswer($quiz, $matches);
                    $this->view->messages->saveMessage("The answer was updated");
                    RedirectHandler::routeTo($this->view->rootAndMethod($matches[self::$methodName]) . "/{$matches[self::$quizIdName]}/{$matches[self::$questionIdName]}");
                } else if (isset($matches[self::$questionIdName])) {
                    $this->editQuestion($quiz, $matches);
                    $this->view->messages->saveMessage("The question was updated");
                    RedirectHandler::routeTo($this->view->rootAndMethod($matches[self::$methodName]) . "/{$matches[self::$quizIdName]}");
                } else if (isset($matches[self::$quizIdName])) {
                    $this->editQuiz($quiz);
                    $this->view->messages->saveMessage("The quiz was updated");
                    RedirectHandler::routeTo($this->view->rootAndMethod($matches[self::$methodName]) . "/");
                }
            } else {
                $this->view->messages->saveMessage("You are not the creator of this quiz");
                RedirectHandler::routeTo("");
            }
            //Request method was GET
        } else {
            if ($quiz !== false) {
                if (UserModel::getCurrentUser()->getId() === $quiz->getCreator()) {
                } else {
                    $this->view->messages->saveMessage("You are not the creator of this quiz");
                    RedirectHandler::routeTo("");
                }
                if (isset($matches[self::$answerIdName])) {
                    /** @var QuestionModel $question */
                    /** @var AnswerModel $answer */
                    /** @var QuizModel $quiz */
                    $question = $quiz->getQuestionById($matches[self::$questionIdName]);
                    $answer = $question->getAnswerById($matches[self::$answerIdName]);

                    return $this->view->getEditAnswerPage($quiz, $question, $answer);
                } else if (isset($matches[self::$questionIdName])) {
                    $question = $quiz->getQuestionById($matches[self::$questionIdName]);

                    return $this->view->getEditQuestionPage($quiz, $question);
                } else if ($quiz !== false) {
                    return $this->view->getEditQuizPage($quiz);
                }

            } else {
                return $this->view->getQuizesPage(true);
            }
        }
    }


    public
    function editAnswer($quiz, $matches) {
        $data = $this->view->getEditData();
        $answer = $quiz->getQuestionById($matches[self::$questionIdName])->getAnswerById($matches[self::$answerIdName]);
        $answer->setAnswertext($data[QuizView::ANSWER_ANSWERTEXT_FORM]);
        $answer->setIscorrect($data[QuizView::ANSWER_ISCORRECT_FORM] == "on" ? 1 : 0);
        $answer->updateAnswer();
    }

    public
    function editQuestion($quiz, $matches) {
        $data = $this->view->getEditData();
        /** @var QuestionModel $question */
        $question = $quiz->getQuestionById($matches[self::$questionIdName]);
        $question->setQuestiontext($data[QuizView::QUESTION_TEXT_FORM]);
        $question->updateQuestion();
    }

    public
    function editQuiz(QuizModel $quiz) {
        $data = $this->view->getEditData();
        $quiz->setName($data[QuizView::QUIZ_NAME_FORM]);
        $quiz->setOpento($data[QuizView::QUIZ_OPENTO_FORM]);
        $quiz->setDescription($data[QuizView::QUIZ_DESCRIPTION_FORM]);
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
            if ($this->view->getTotallySure() === QuizView::TOTALLY_SURE_VALUE) {
                if (isset($matches[self::$questionIdName])) {
                    /** @var QuizModel $quiz */
                    $question = $quiz->getQuestionById($matches[self::$questionIdName]);
                    $question->removeQuestion();
                    $this->view->messages->saveMessage("The question was removed");
                    RedirectHandler::routeTo($this->view->rootAndMethod(QuizView::EDIT_METHOD_NAME) . "/" . $quiz->getId());
                } else if (isset($matches[self::$answerIdName])) {
                    $question = $quiz->getQuestionById($matches[self::$questionIdName]);
                    $answer = $question->getAnswerById($matches[self::$answerIdName]);
                    $answer->removeAnswer();
                    $this->view->messages->saveMessage("The answer was removed");
                    RedirectHandler::routeTo($this->view->rootAndMethod(QuizView::EDIT_METHOD_NAME) . "/" . $quiz->getId() . "/" . $question->getId());
                } else if (isset($matches[self::$quizIdName])) {
                    $quiz = $this->quizList->getQuizById($matches[self::$quizIdName]);
                    $quiz->removeQuiz();
                    $this->view->messages->saveMessage("The quiz was removed");
                    RedirectHandler::routeTo($this->view->rootAndMethod(QuizView::EDIT_METHOD_NAME) . "/");
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
            if (isset($matches[self::$questionIdName])) {
                $this->createAnswer($matches);
                $this->view->messages->saveMessage("The answer was created");
                RedirectHandler::routeTo($this->view->rootAndMethod($matches[self::$methodName]) . "/{$matches[self::$quizIdName]}/{$matches[self::$questionIdName]}");
            } else if (isset($matches[self::$quizIdName])) {
                $this->createQuestion($matches);
                $this->view->messages->saveMessage("The question was created");
                RedirectHandler::routeTo($this->view->rootAndMethod($matches[self::$methodName]) . "/{$matches[self::$quizIdName]}");
            } else {
                $this->createQuiz();
                $this->view->messages->saveMessage("The quiz was created");
                RedirectHandler::routeTo($this->view->rootAndMethod(QuizView::EDIT_METHOD_NAME) . "/");
            }
            //Request method was GET
        } else {
            if (isset($matches[self::$questionIdName])) {
                $quiz = $this->quizList->getQuizById($matches[self::$quizIdName]);
                $question = $quiz->getQuestionById($matches[self::$questionIdName]);

                return $this->view->getAddAnswerPage($quiz, $question);
            } else if (isset($matches[self::$quizIdName])) {
                $quiz = $this->quizList->getQuizById($matches[self::$quizIdName]);

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
        $answer->setQuestionid($matches[self::$questionIdName]);
        $answer->setAnswertext($data[QuizView::ANSWER_ANSWERTEXT_FORM]);
        $answer->setIscorrect($data[QuizView::ANSWER_ISCORRECT_FORM] == "on" ? 1 : 0);
        $answer->saveAnswer();
    }

    public
    function createQuestion($matches) {
        $data = $this->view->getAddData();
        /** @var QuestionModel $question */
        $question = new QuestionModel();
        $question->setQuestiontext($data[QuizView::QUESTION_TEXT_FORM]);
        $question->setQuizid($matches[self::$quizIdName]);
        $question->addQuestion();
    }

    public
    function createQuiz() {
        $data = $this->view->getAddData();
        $quiz = new QuizModel();
        $quiz->setDescription($data[QuizView::QUIZ_DESCRIPTION_FORM]);
        $quiz->setCreator(UserModel::getCurrentUser()->getId());
        $quiz->setName($data[QuizView::QUIZ_NAME_FORM]);
        $quiz->setOpento($data[QuizView::QUIZ_OPENTO_FORM]);
        $quiz->addQuiz();
    }

    private
    function handleResult($quiz, $requestMethod, $matches) {
        return $this->view->getResultPage($quiz, UserModel::getCurrentUser());
    }
}