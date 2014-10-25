<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-12
 * Time: 16:16
 */
require_once("QuizModel.php");
require_once("QuestionModel.php");
require_once("AnswerModel.php");

class QuizList {
    private $quizes;

    public function __construct() {
        $this->quizes = $this->loadQuizes();

        //$question = new QuestionModel();
        //$question->addAnswers(array(new AnswerModel("Whatnow", true)));

        //$this->quizes = array(new QuizModel(array($question)));
    }

    /**
     *  This might not be the optimal way and will probably depend on something like the user that is logged in
     *  (or not logged in)
     */
    private function loadQuizes() {
        return QuizModel::getAllQuizes();
    }

    static public function getPopular() {
        return QuizModel::getMostPopularQuizes();
    }

    static public function getMostDone() {
        return QuizModel::getMostDoneQuizes();
    }
    public function getAllQuizes() {
        return $this->quizes;
    }

    /**
     * This is really ugly and will maybe break, but what ever
     * Since the database rows start at 1 and array indexes start at 0 we must subtract 1 from the $qid
     * @param $qid
     * @return QuizModel
     */
    public function getQuizById($qid) {
        /** @var QuizModel $quizes */
        foreach ($this->quizes as $quizes) {
            if ($quizes->getId() == $qid) {
                return $quizes;
            }
        }

        return false;
    }
}