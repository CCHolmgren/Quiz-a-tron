<?php
defined("__ROOT__") or die("Noh!");

/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 13:01
 */

require_once("AnswerList.php");
require_once("QuestionList.php");
class QuestionModel extends Model {
    private $id;
    private $answers;
    private $questiontext;
    private $rightAnswers;
    private $wrongAnswers;
    private $quizid;

    public function __construct() {
        $this->answers = new AnswerList();
        $this->rightAnswers = new AnswerList();
        $this->wrongAnswers = new AnswerList();
        $this->loadAnswers();
    }

    private function loadAnswers() {
        $conn = $this->getConnection();
        $sth = $conn->prepare("SELECT * FROM answers WHERE questionid = ?");
        $sth->execute(array($this->id));

        while ($object = $sth->fetchObject("AnswerModel")) {
            $this->answers->addAnswer($object);
            if ($object->getIscorrect() === 1) {
                $this->rightAnswers->addAnswer($object);
            } else {
                if ($object->getIscorrect() === 0) {
                    $this->wrongAnswers->addAnswer($object);
                }
            }
        }
    }

    /**
     * Adds the answers to this->answers, $this->rightAnswers and $this->wrongAnswers based on if getIscorrect()
     * @param array $answers
     */
    public function addAnswers(AnswerList $answers) {
        /** @var AnswerModel $answer */
        foreach ($answers as $answer) {
            $this->answers->addAnswer($answer);
            if ($answer->getIsCorrect()) {
                $this->rightAnswers->addAnswer($answer);
            } else {
                $this->wrongAnswers->addAnswer($answer);
            }
        }
    }

    /**
     * @param $id
     * @return null|AnswerModel
     */
    public function getAnswerById($id) {
        /** @var AnswerModel $answer */
        foreach ($this->answers as $answer) {
            if ($answer->getId() == $id) {
                return $answer;
            }
        }

        return null;
    }

    /**
     * You must answer all correct and nothing wrong to get a allCorrect
     * Returns the right and the wrong of the answered questions
     * @param array $data Array with the answer id's
     * @return array The keys of the $data array with either true or false if it was a correct answer or a wrong one
     * the $result array will also have a onlyCorrect key set to either true or false based on if it was all the correct
     * answers that was available or not
     */
    public function validateAnswers(array $data) {
        if (!$data) {
            $result["onlyCorrect"] = false;
            $result["countRightAnswers"] = 0;
            $result["countWrongAnswers"] = 0;
            $result["rightAnswerCount"] = $this->getCountRightAnswers();
            $result["wrongAnswerCount"] = $this->getCountWrongAnswers();

            return $result;
        }
        //Anonymous functions in PHP? Aw yess
        //Make an array of all the answers id's
        /**
         * @param $answer AnswerModel
         * @return integer
         */
        $getIdsOfAnswers = function ($answer) {
            return $answer->getId();
        };
        //So we can get all rightAnswers and loop through them
        $rightAnswersIds = array_map($getIdsOfAnswers, $this->rightAnswers);
        $wrongAnswersIds = array_map($getIdsOfAnswers, $this->wrongAnswers);

        /*
         * Count the amount of right answers
         * If we get a wrong answer, then we do not keep the streak and we reset it to 0
         */
        $rightStreak = 0;
        $wrongCount = 0;
        $rightCount = 0;

        $result = array();
        foreach ($data as $key => $answer) {
            if (in_array($answer, $rightAnswersIds)) {
                $result[$key] = true;
                $rightStreak += 1;
                $rightCount += 1;
            } else {
                if (in_array($answer, $wrongAnswersIds)) {
                    $result[$key] = false;
                    $rightStreak = 0;
                    $wrongCount += 1;
                }
            }
        }
        //More anonymous functions, oh yes
        $countRightAnswers = function ($carry, $item) {
            if ($item === true) {
                $carry += 1;
            }

            return $carry;
        };
        $countWrongAnswers = function ($carry, $item) {
            if ($item === false) {
                $carry += 1;
            }

            return $carry;
        };
        $result["countRightAnswers"] = array_reduce($result, $countRightAnswers, 0);
        $result["countWrongAnswers"] = array_reduce($result, $countWrongAnswers, 0);
        //Here we check to see if we only got correct answers, we could do it some other way,
        //but I think this is the nicest
        if ($rightStreak === $this->getCountRightAnswers() || $this->getCountRightAnswers() === 0 && $wrongCount !== 0) {
            $result["onlyCorrect"] = true;
        } else {
            $result["onlyCorrect"] = false;
        }
        $result["rightAnswerCount"] = $this->getCountRightAnswers();
        $result["wrongAnswerCount"] = $this->getCountWrongAnswers();

        return $result;
    }

    public function getCountRightAnswers() {
        return count($this->rightAnswers);
    }

    public function getCountWrongAnswers() {
        return count($this->wrongAnswers);
    }

    public function addQuestion() {
        $conn = $this->getConnection();
        $sth = $conn->prepare("INSERT INTO questions (questiontext, quizid) VALUES(?,?) RETURNING id");
        $sth->execute(array($this->questiontext, $this->quizid));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        $this->id = $result["id"];
    }

    public function saveQuestion($quizId) {
        $conn = $this->getConnection();
        $sth = $conn->prepare("INSERT INTO questions (questiontext, quizid) VALUES(?,?) RETURNING id");
        $sth->execute(array($this->questiontext, $quizId));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        $this->id = $result["id"];

        /** @var AnswerModel $answer */
        foreach ($this->answers as $answer) {
            $answer->saveAnswer($this->id);
        }

        return;
    }

    public function updateQuestion() {
        $conn = $this->getConnection();
        $sth = $conn->prepare("UPDATE questions SET questiontext = ? WHERE id = ?");

        $sth->execute(array($this->questiontext, $this->id));

    }

    public function removeQuestion() {
        $conn = $this->getConnection();
        $sth = $conn->prepare("DELETE FROM questions WHERE id = ?");

        $sth->execute(array($this->id));
    }

    public function getCountAnswers() {
        return count($this->answers);
    }

    /**
     * @return AnswerList
     */
    public function getAnswers() {
        return $this->answers;
    }

    /**
     * @param array $answers
     */
    public function setAnswers($answers) {
        $this->answers = $answers;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getQuestionText() {
        return $this->questiontext;
    }

    /**
     * @param mixed $questionText
     */
    public function setQuestiontext($questiontext) {
        $this->questiontext = $questiontext;
    }

    /**
     * @return array
     */
    public function getRightAnswers() {
        return $this->rightAnswers;
    }

    /**
     * @param array $rightAnswers
     */
    public function setRightAnswers($rightAnswers) {
        $this->rightAnswers = $rightAnswers;
    }

    /**
     * @return array
     */
    public function getWrongAnswers() {
        return $this->wrongAnswers;
    }

    /**
     * @param array $wrongAnswers
     */
    public function setWrongAnswers($wrongAnswers) {
        $this->wrongAnswers = $wrongAnswers;
    }

    /**
     * @return mixed
     */
    public function getQuizid() {
        return $this->quizid;
    }

    /**
     * @param mixed $quizId
     */
    public function setQuizid($quizid) {
        $this->quizid = $quizid;
    }
}