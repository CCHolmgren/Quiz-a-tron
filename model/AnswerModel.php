<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 13:03
 */
class AnswerModel extends Model{
    private $answerText;
    private $isCorrect;

    public function __construct($text, $iscorrect){
        $this->answerText = $text;
        $this->isCorrect = $iscorrect;
    }
}