<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 13:03
 */
class AnswerModel extends Model{
    private $id;
    private $answertext;
    private $iscorrect;
    private $questionid;

    public function __construct()
    {
        /*$this->answertext = $text;
        $this->iscorrect = $iscorrect;
        $this->questionid = $questionId;*/
    }

    /**
     * @return string
     */
    public function getAnswertext()
    {
        return $this->answertext;
    }

    /**
     * @param string $answertext
     */
    public function setAnswertext($answertext)
    {
        $this->answertext = $answertext;
    }

    /**
     * @return integer
     */
    public function getIscorrect()
    {
        return $this->iscorrect;
    }

    /**
     * Because PDO and postgresql boolean fields doesnt want to play together we must use the integer representation
     * instead
     * @param integer $iscorrect
     */
    public function setIscorrect($iscorrect)
    {
        $this->iscorrect = $iscorrect;
    }

    /**
     * @return int
     */
    public function getQuestionid()
    {
        return $this->questionid;
    }

    /**
     * @param int $questionid
     */
    public function setQuestionid($questionid)
    {
        $this->questionid = $questionid;
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

}