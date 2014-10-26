<?php
defined("__ROOT__") or die("Noh!");

/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 13:03
 */
class AnswerModel extends Model {
    private $id;
    private $answertext;
    private $iscorrect;
    private $questionid;

    public function __construct($answertext = null, $iscorrect = null) {
        if ($answertext !== null) {
            $this->answertext = $answertext;
        }
        if ($iscorrect !== null) {
            $this->iscorrect = $iscorrect;
        }
        //$this->questionid = $questionId;
    }

    /**
     * @return string
     */
    public function getAnswertext() {
        return $this->answertext;
    }

    /**
     * @param string $answertext
     */
    public function setAnswertext($answertext) {
        $this->answertext = $answertext;
    }

    /**
     * @return integer
     */
    public function getIscorrect() {
        return $this->iscorrect;
    }

    /**
     * Because PDO and postgresql boolean fields doesnt want to play together we must use the integer representation
     * instead
     * @param integer $iscorrect
     */
    public function setIscorrect($iscorrect) {
        $this->iscorrect = $iscorrect;
    }

    /**
     * @return int
     */
    public function getQuestionid() {
        return $this->questionid;
    }

    /**
     * @param int $questionid
     */
    public function setQuestionid($questionid) {
        $this->questionid = $questionid;
    }

    public function updateAnswer() {
        $conn = $this->getConnection();
        $sth =
            $conn->prepare("UPDATE answers SET (answertext, iscorrect, questionid) = (:answertext,:iscorrect,:questionid) WHERE id = :answerid");
        $sth->execute(array("answertext" => $this->answertext, "iscorrect" => $this->iscorrect, "questionid" => $this->questionid, "answerid" => $this->id));

        return;
    }

    public function saveAnswer() {
        $conn = $this->getConnection();
        $sth =
            $conn->prepare("INSERT INTO answers(answertext, iscorrect, questionid) VALUES(:answertext,:iscorrect,:questionid) RETURNING id");
        $sth->execute(array("answertext" => $this->answertext, "iscorrect" => $this->iscorrect, "questionid" => $this->questionid));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        $this->id = $result["id"];

        return;
    }

    public function removeAnswer() {
        $conn = $this->getConnection();
        $sth = $conn->prepare("DELETE FROM answers WHERE id = ?");
        $sth->execute(array($this->getId()));
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

}