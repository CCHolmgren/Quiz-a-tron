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

class QuizList implements Iterator {
    private $quizes;

    public function __construct($load = true, $quizes = []) {
        if ($load) {
            $this->quizes = $this->loadQuizes();
        } else {
            $this->quizes = $quizes;
        }
        //$question = new QuestionModel();
        //$question->addAnswers(array(new AnswerModel("Whatnow", true)));

        //$this->quizes = array(new QuizModel(array($question)));
    }

    /**
     *  This might not be the optimal way and will probably depend on something like the user that is logged in
     *  (or not logged in)
     */
    private function loadQuizes($asQuizList = false) {
        if ($asQuizList) {
            return new QuizList(false, QuizModel::getAllQuizes());
        }

        return QuizModel::getAllQuizes();
    }

    static public function getPopular($asQuizList = false) {
        if ($asQuizList) {
            return new QuizList(false, QuizModel::getMostPopularQuizes());
        }

        return QuizModel::getMostPopularQuizes();
    }

    static public function getMostDone($asQuizList = false) {
        if ($asQuizList) {
            return new QuizList(false, QuizModel::getMostDoneQuizes());
        }
        return QuizModel::getMostDoneQuizes();
    }

    public function getAllQuizes() {
        return $this;
    }

    public function getByCreator($creatorid) {
        /** @var QuizModel $quiz */
        $result = [];
        foreach ($this->quizes as $quiz) {
            if ($quiz->getCreator() === $creatorid) {
                $result[] = $quiz;
            }
        }

        return new QuizList(false, $result);
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

    public function rewind() {
        reset($this->quizes);
    }

    public function current() {
        return current($this->quizes);
    }

    public function key() {
        return key($this->quizes);
    }

    public function next() {
        return next($this->quizes);
    }

    public function valid() {
        $key = key($this->quizes);
        $var = ($key !== null && $key !== false);

        return $var;
    }
}