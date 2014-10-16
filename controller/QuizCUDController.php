<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-16
 * Time: 12:09
 */
require_once("Controller.php");

class QuizCUDController extends Controller
{
    private $view;

    public function __construct()
    {
        $this->view = new QuizView();
        $this->quizList = new QuizList();
        parent::__construct();
    }

    protected function __getHTML($route)
    {
        return "Hello there, you are in the QuizCUDController, what a great name, huh?";

    }
}