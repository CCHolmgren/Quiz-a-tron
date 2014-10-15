<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 15:46
 */
require_once(__ROOT__ . "model/QuizList.php");

class QuizView extends View
{
    private $quizmodel;
    private $quizes;

    public function __construct()
    {
        $this->quizList = new QuizList();
        $this->quizes = $this->quizList->getAllQuizes();
    }

    /**
     * @return string
     */
    public function getQuizesPage()
    {
        $html = '';
        foreach ($this->quizes as $quiz) {
            $html .= $quiz->description . "<a href='?/quizes/quiz/{$quiz->getId()}'>Whatnow</a>";
        }
        return $html;
    }

    /**
     * @param $quizid Integer representing the id of the quiz that we should display
     * @return string Html that represnts the form and all that is relevant about the quiz
     */
    public function getQuizPage($quiz)
    {
        /** @var QuizModel $quiz */
        $html = "";
        if ($quiz) {
            /** @var QuestionModel $question */
            $questions = $quiz->getQuestions();
            $html .= "<form method='post'>";
            foreach ($questions as $question) {
                $html .= "These are the answers<br>";

                /** @var AnswerModel $answer */
                $html .= $question->getQuestionText() . "<br>";
                foreach ($question->getAnswers() as $answer) {
                    $html .= "<span>" . $answer->getAnswertext() . "</span>";
                    $html .= $answer->getIscorrect();
                    $html .= $answer->getId();
                    $html .= "<input type='checkbox' name='{$question->getId()}[]' value={$answer->getId()}>";
                    $html .= "<br/>";
                }
            }
            $html .= "<input type='submit' value='Submit answers'>";
            $html .= "</form>";
        }
        return $html;
    }

    public function getResultsPage($result, $quiz)
    {
        $html = "";
        foreach ($result as $resultRow) {
            $html .= var_export($resultRow, true);
        }
        return $html;
    }
}