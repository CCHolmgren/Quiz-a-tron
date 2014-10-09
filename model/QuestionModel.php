<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 13:01
 */
class QuestionModel extends Model{
    private $answers;
    private $rightAnswers;
    private $wrongAnswers;

    public function __construct(){
        $this->answers = array();
        $this->rightAnswers = array();
        $this->wrongAnswers = array();
    }
}