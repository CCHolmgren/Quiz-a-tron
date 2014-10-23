<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 15:46
 */
require_once(__ROOT__ . "model/QuizList.php");

class QuizView extends View {
    private $quizmodel;
    private $quizes;

    public function __construct() {
        $this->quizList = new QuizList();
        $this->quizes = $this->quizList->getAllQuizes();
    }

    /**
     * @param $quizid Integer representing the id of the quiz that we should display
     * @return string Html that represnts the form and all that is relevant about the quiz
     */
    public function getQuizPage($quiz) {
        /** @var QuizModel $quiz */
        $html = "";
        if ($quiz) {
            /** @var QuestionModel $question */
            $questions = $quiz->getQuestions();
            if (!$questions) {
                return "<p>There seems to be no questions in this quiz. How odd...</p>";
            }
            $html .= "<form method='post'>";
            foreach ($questions as $question) {

                $html .= "<div class='question'>";


                /** @var AnswerModel $answer */
                $html .= $question->getQuestionText() . "<br>";
                foreach ($question->getAnswers() as $answer) {
                    $html .= "<div class='answer' style='border:1px solid grey'>";
                    $html .= "<span class='answertext'>" . $answer->getAnswertext() . "</span>";
                    $html .= "<input type='checkbox' name='{$question->getId()}[]' value={$answer->getId()}>";
                    $html .= "<br/>";

                    $html .= "</div>";
                }
                $html .= "</div>";
            }
            $html .= "<input type='submit' value='Submit answers'>";
            $html .= "</form>";
        }

        return $html;
    }

    public function getResultsPage($result, $quiz) {
        $html = "";
        $html .= "<h3>Result</h3>";
        echo "Current user";
        var_dump(UserModel::getCurrentUser());
        foreach ($result as $key => $resultRow) {
            //Typesafety in PHP is, well, really bad, but I do not want the string keys, just the resultrows that contain the good info

            if (gettype($key) === "integer") {
                $questionCount = $key + 1;
                $html .= "<h4>Question $questionCount</h4>";
                $html .= $resultRow["countRightAnswers"];
                $html .= " out of " . $resultRow["rightAnswerCount"];
                if ($resultRow["countWrongAnswers"] > 0) {
                    $html .= " with " . $resultRow["countWrongAnswers"] . " extra wrong answers.";
                } else {
                    $html .= "!";
                }

            }
            if ($key === "allCorrect" && $resultRow["allCorrect"] === true) {
                $html .= "<p>Wow you got all the questions correct!</p>";
            }
        }

        return $html;
    }

    public function getEditQuizPage(QuizModel $quiz) {
        $html = "This will require just as much as the other one. Hold on for a long while until I fix this.";
        $html .= "<form method='post'>";
        $html .= "<input type='text' name='quiztext' value='" . $quiz->getDescription() . "'>";
        $html .= "<input type='submit' value='Save'>";
        $html .= "</form>";
        $html .= "<a href='/project/quizes/add/{$quiz->getId()}'>Add questions</a>";
        /*
        $html .= "
                <form method='post'>";*/
        /** @var QuestionModel $question */
        if ($quiz->getQuestions() !== null) {

            foreach ($quiz->getQuestions() as $question) {
                $html .= $question->getQuestionText();
                $html .= "<a href='/project/quizes/edit/{$quiz->getId()}/{$question->getId()}'>Edit</a>";
                $html .= "<a href='/project/quizes/delete/{$quiz->getId()}/{$question->getId()}'>Delete</a>";
                $html .= "<br>";
                /*
                $html .= "<input type='hidden' name='objecttype;question;{$question->getId()}' value='question'>";
                $html .= "<input type='hidden' name='parentid' value='{$quiz->getId()}'>";
                $html .= "<input type='textbox' name='questiontext;{$question->getId()}' value='" . $question->getQuestionText() . "'><br>";
                */
                /** @var AnswerModel $answers */
                /*
                foreach($question->getAnswers() as $answers){
                    $html .= "<input type='hidden' name='objecttype;answer;{$answers->getId()}'>";
                    $html .= "<input type='hidden' name='parentid' value='{$question->getId()}'>";
                    //HTML is ugly when it comes to checkboxes
                    $html .= "<input type='checkbox' name='iscorrect;{$answers->getId()}' " . ($answers->getIscorrect() === 1 ? "checked='true'" : "") ."/>";
                    $html .= "<input type='textbox' name='answertext;{$answers->getId()}' value='" . $answers->getAnswertext() . "'>";
                    $html .= "<br>";
                }*/
            }
        }

        /*$html .= "<input type='submit' value='Save this quiz'>";
        $html .= "</form>";*/

        return $html;
    }

    public function getQuestionPage(QuizModel $quiz, QuestionModel $question) {
        $html = "";
        $html .= "<form method='post'>";
        $html .= "<input type='text' name='questiontext' value='" . $question->getQuestionText() . "'>";
        $html .= "<input type='submit' value='Save'>";
        $html .= "</form>";
        $html .= "<a href='/project/quizes/add/{$quiz->getId()}/{$question->getId()}'>Add answers</a>";
        /** @var AnswerModel $answer */
        foreach ($question->getAnswers() as $answer) {

            $html .= $answer->getAnswertext();
            $html .= $answer->getIscorrect();
            $html .= "<a href='/project/quizes/edit/{$quiz->getId()}/{$question->getId()}/{$answer->getId()}'>Edit</a>";
            $html .= "<a href='/project/quizes/delete/{$quiz->getId()}/{$question->getId()}/{$answer->getId()}'>Delete</a>";
            $html .= "<br>";
        }

        return $html;
    }

    public function getEditAnswerPage(QuizModel $quiz, QuestionModel $question, AnswerModel $answer) {
        $html = "You are now in the Answer page";
        $html .= "<form method='post'>";
        $html .= "<input type='text' name='answertext' value='{$answer->getAnswertext()}'>";
        //HTML please
        $html .= "<input type='hidden' name='iscorrect' value='off'>";
        $html .= "<input type='checkbox' name='iscorrect' " . ($answer->getIscorrect() == 1 ? "checked" : "") . ">";
        $html .= "<input type='submit' value='Save'>";
        $html .= "</form>";

        return $html;
    }

    public function getAddAnswerPage(QuestionModel $question) {
        $html = "You are now in the add answer page";
        /** @var AnswerModel $answer */
        foreach ($question->getAnswers() as $answer) {
            $html .= $answer->getAnswertext();
            $html .= $answer->getIscorrect();
            $html .= "<a href='/project/quizes/edit/{$question->getQuizid()}/{$question->getId()}/{$answer->getId()}'>Edit</a>";
            $html .= "<a href='/project/quizes/delete/{$question->getId()}/{$question->getId()}/{$answer->getId()}'>Delete</a>";
            $html .= "<br>";
        }
        $html .= "<form method='post'>";
        $html .= "<input type='text' name='answertext' value=''>";
        //HTML please
        $html .= "<input type='hidden' name='iscorrect' value='off'>";
        $html .= "<input type='checkbox' name='iscorrect' >";
        $html .= "<input type='submit' value='Save'>";
        $html .= "</form>";

        return $html;
    }

    public function getAddQuestionPage(QuizModel $quiz) {
        $html = "<h1>You are now in the add question page</h1>";
        $html .= "<p class='lead'>The other questions in the quiz:</p>";
        /** @var QuestionModel $question */
        foreach ($quiz->getQuestions() as $question) {

            $html .= "<div class=''>";
            $html .= "<p class=''><a class='btn btn-default btn-xs' role='button' href='/project/quizes/edit/{$quiz->getId()}/{$question->getId()}'>Edit</a>";
            $html .= "<a class='btn btn-danger btn-xs' role='button'  href='/project/quizes/delete/{$quiz->getId()}/{$question->getId()}'>Delete</a>";
            $html .= " " . $question->getQuestionText() . "</p>";

            $html .= "</div>";
        }

        $html .= "<form method='post' role='form'>";
        $html .= "<div class='form-group'>";
        $html .= "<label for='questiontext'>Question text</label>";
        $html .= "<input type='text' name='questiontext' value='' class='form-control' placeholder='Enter a question text'>";
        $html .= "</div>";
        $html .= "<input type='submit' value='Save' class='btn btn-primary btn-lg btn-block'>";
        $html .= "</form>";

        return $html;
    }

    public function getAddQuizPage() {
        $html = "This will require a lot of things. Hold on for a long while until I fix this.";
        $html .= "<form method='post'>";
        $html .= "<input type='text' name='name' value=''>";

        $html .= "<input type='text' name='description' value=''>";
        $html .= "<input type='text' name='opento' value=''>";
        $html .= "<input type='submit' value='Save'>";
        $html .= "</form>";

        return $html;
    }

    public function getRemoveQuizPage(QuizModel $quiz) {
        $html = "";
        $html .= "
                    <form method='post'>
                        <p>Are you totally sure that you want to delete this quiz? It can't be undone and it will erase everything associated with that quiz.</p>
                        <input type='hidden' name='totallysure' value='true'>
                        <input type='submit' value='Delete' class='btn btn-danger'>
                    </form>";

        return $html;
    }

    public function getCUDPage() {
        $html = "Hello this is the CUD page, what can I do for you?";
        $html .= $this->getQuizesPage(true);

        return $html;
    }

    /**
     * @return string
     */
    public function getQuizesPage($editMethods = false) {
        $html = '<h3>Hello there. This is the quiz page. These are all the quizes available for you:</h3>';
        $html .= "<table class='table table-striped table-condensed'>
                    <thead>
                        <tr>
                            <th>Quiz name</th>
                            <th>Description</th>
                            <th>Questions</th>
                            <th>Done?</th>
                            <th>Link</th>";
        if ($editMethods) {
            $html .= "<th>Methods</th>";
        }
        $html .= "
                        </tr>
                    </thead>
                    <tbody>";
        /** @var QuizModel $quiz */
        sort($this->quizes);
        foreach ($this->quizes as $quiz) {
            if (mb_strlen($quiz->getDescription()) - 3 > 31) {
                $descriptionText = mb_substr($quiz->getDescription(), 0, 30) . "...";
            } else {
                $descriptionText = $quiz->getDescription();
            }
            if (mb_strlen($quiz->getName()) - 3 > 26) {
                $quizName = mb_substr($quiz->getName(), 0, 25) . "...";
            } else {
                $quizName = $quiz->getName();
            }
            if (UserModel::getCurrentUser()->hasDoneQuiz($quiz->getId())) {
                $hasDone = "Yes";
            } else {
                $hasDone = "No";
            }

            $html .= "<tr><td>";
            $html .= $quizName . "</td><td>" . $descriptionText . "</td><td>" . $quiz->getQuestionCount() . "</td><td>" . $hasDone . "</td><td>" . "<a href='/project/quizes/quiz/{$quiz->getId()}'>Go do this quiz!</a></td>";

            if ($editMethods) {
                $html .= "<td><a href='/project/quizes/edit/{$quiz->getId()}'>Edit</a> | <a href='/project/quizes/delete/{$quiz->getId()}'>Delete</a></td>";
            }
            $html .= "</tr>";
        }
        $html .= "</tbody></table>";

        return $html;
    }

    public function getTotallySure() {
        return $_POST["totallysure"];
    }

    public function getAddData() {
        return $_POST;
    }

    public function getEditData() {
        return $_POST;
    }
}