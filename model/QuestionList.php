<?php

/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-11-01
 * Time: 11:09
 */
class QuestionList implements Iterator {
    private $questions;

    public function __construct() {
        $this->questions = [];
    }

    public function addQuestion(QuestionModel $question) {
        $this->questions[] = $question;
    }

    public function rewind() {
        reset($this->questions);
    }

    public function current() {
        return current($this->questions);
    }

    public function key() {
        return key($this->questions);
    }

    public function next() {
        return next($this->questions);
    }

    public function valid() {
        $key = key($this->questions);
        $var = ($key !== null && $key !== false);

        return $var;
    }
}