<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 12:59
 */
require_once("Model.php");

class QuizModel extends Model{
    private $id;
    private $name;
    private $creator;
    private $questions;
    private $openTo;

    public function __construct(){
        $this->questions = array();
    }
}