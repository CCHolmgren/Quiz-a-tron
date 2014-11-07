<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-31
 * Time: 13:51
 */
require_once("AnswerModel.php");

class AnswerList implements Iterator {
    private $answers;

    /**
     * @param AnswerModel $answers
     */
    public function addAnswer(AnswerModel $answer) {
        $this->answers[] = $answer;
    }

    public function rewind() {
        reset($this->answers);
    }

    /**
     * @return AnswerModel
     */
    public function current() {
        return current($this->answers);
    }

    /**
     * @return Integer
     */
    public function key() {
        return key($this->answers);
    }

    /**
     * @return AnswerModel
     */
    public function next() {
        return next($this->answers);
    }

    /**
     * @return bool
     */
    public function valid() {
        $key = key($this->answers);
        $var = ($key !== null && $key !== false);

        return $var;
    }

    public function count() {
        return count($this->answers);
    }
}