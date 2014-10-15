<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 13:01
 */
class QuestionModel extends Model{
    private $id;
    private $answers;
    private $questiontext;
    private $rightAnswers;
    private $wrongAnswers;
    private $quizid;

    public function __construct(){
        $this->answers = array();
        $this->rightAnswers = array();
        $this->wrongAnswers = array();
        $this->loadAnswers();
    }

    private function loadAnswers()
    {
        $conn = $this->getConnection();
        $sth = $conn->prepare("SELECT * FROM answers WHERE questionid = ?");
        $sth->execute(array($this->id));

        while ($object = $sth->fetchObject("AnswerModel")) {
            $this->answers[] = $object;
            if ($object->getIscorrect() === 1) {
                $this->rightAnswers[] = $object;
            } else if ($object->getIscorrect() === 0) {
                $this->wrongAnswers[] = $object;
            }
        }
    }

    /**
     * Adds the answers to this->answers, $this->rightAnswers and $this->wrongAnswers based on if getIscorrect()
     * @param array $answers
     */
    public function addAnswers(array $answers)
    {
        /** @var AnswerModel $answer */
        foreach ($answers as $answer) {
            $this->answers[] = $answer;
            if ($answer->getIsCorrect()) {
                $this->rightAnswers[] = $answer;
            } else {
                $this->wrongAnswers[] = $answer;
            }
        }
    }

    public function getAnswerById($id)
    {
        foreach ($this->answers as $answer) {
            if ($answer->getId() === $id) {
                return $answer;
            }
        }
    }

    public function validateAnswers(array $data)
    {
        return true;
    }

    /**
     * @return array
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param array $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getQuestionText()
    {
        return $this->questiontext;
    }

    /**
     * @param mixed $questionText
     */
    public function setQuestionText($questionText)
    {
        $this->questionText = $questionText;
    }

    /**
     * @return array
     */
    public function getRightAnswers()
    {
        return $this->rightAnswers;
    }

    /**
     * @param array $rightAnswers
     */
    public function setRightAnswers($rightAnswers)
    {
        $this->rightAnswers = $rightAnswers;
    }

    /**
     * @return array
     */
    public function getWrongAnswers()
    {
        return $this->wrongAnswers;
    }

    /**
     * @param array $wrongAnswers
     */
    public function setWrongAnswers($wrongAnswers)
    {
        $this->wrongAnswers = $wrongAnswers;
    }

    /**
     * @return mixed
     */
    public function getQuizId()
    {
        return $this->quizid;
    }

    /**
     * @param mixed $quizId
     */
    public function setQuizId($quizId)
    {
        $this->quizId = $quizId;
    }
}