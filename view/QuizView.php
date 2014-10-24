<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 15:46
 */
require_once(__ROOT__ . "model/QuizList.php");
require_once(__ROOT__ . "helpers/StringHelper.php");

class QuizView extends View {
    public static $editMethodName = "edit";
    public static $addMethodName = "add";
    public static $removeMethodName = "delete";
    public static $resultMethodName = "result";
    private $quizes;
    private $pd;

    public function __construct() {
        $this->pd = new Parsedown();
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
        } else {
            return $this->getQuizMissingPage();
        }

        return $html;
    }

    public function getQuizMissingPage() {
        $html = "";
        $html .= "This quiz is not available.";

        return $html;
    }

    public function removeDisallowedTags($string, $allowedTags = "<b><p><strong><i><code>") {
        return strip_tags($this->stripAttributes($string), $allowedTags);
    }

    public function stripAttributes($string) {
        return preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i", '<$1$2>', $string);
    }

    public function getEditQuizPage(QuizModel $quiz) {
        $html = "This will require just as much as the other one. Hold on for a long while until I fix this.";
        $html .= $this->getAddButton("Add questions", "/{$quiz->getId()}", "btn-default");

        /** @var QuestionModel $question */
        if ($quiz->getQuestionCount()) {
            $html .= "<p class=''>The other questions in the quiz:</p>";
            /** @var QuestionModel $question */
            $html .= "
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>Question text</th>
                                    <th>Amount of answers</th>
                                    <th>Methods</th>
                                </tr>
                            </thead>
                            <tbody>";
            foreach ($quiz->getQuestions() as $question) {

                $html .= "<tr>";
                $html .= "<td>" . $this->text($question->getQuestionText()) . "</td>";
                $html .= "<td>" . $question->getCountAnswers() . "</td>";
                $html .= "<td class=''>" . $this->getEditButton('Edit', '/' .
                        $quiz->getId() . '/' .
                        $question->getId(),
                                                                'btn-default btn-xs');
                $html .= $this->getRemoveButton("Delete", "/" .
                        $quiz->getId() . "/" .
                        $question->getId(),
                                                "btn-danger btn-xs") . "</td>";

                $html .= "</tr>";
            }
            $html .= "</tbody></table>";
        } else {
            $html .= "<p class=''>There seems to be no other questions in this quiz yet.</p>";
        }
        $html .= $this->getQuizForm($quiz);

        return $html;
    }

    public function getAddButton($text = "Add quiz", $extra = "", $class = "btn-default") {
        return $this->getButton($this->rootAndMethod(QuizView::$addMethodName) . $extra, $class, $text);
    }

    private function getButton($href, $class, $text) {
        return "<a href='$href' class='btn $class'>$text</a>";
    }

    public function rootAndMethod($editMethod) {
        $result = View::$rootBase . "quizes/" . $editMethod;

        return $result;
    }

    public function text($string) {
        return $this->pd->text(strip_tags($string));
    }

    public function getEditButton($text = "Edit quiz", $extra = "", $class = "btn-default") {
        return $this->getButton($this->rootAndMethod(QuizView::$editMethodName) . $extra, $class, $text);
    }

    public function getRemoveButton($text = "Remove quiz", $extra = "", $class = "btn-danger") {
        return $this->getButton($this->rootAndMethod(QuizView::$removeMethodName) . $extra, $class, $text);
    }

    public function getQuizForm($quiz) {
        $html = "
                    <form method='post'>
                        <div class='form-group'>
                            <label for='name'>Quiz name</label>
                            <input type='text' name='name' class='form-control' value='" . $quiz->getName() . "'>
                        </div>
                        <div class='form-group'>
                            <label for='description'>Quiz description</label>
                            <textarea name='description' class='form-control'>" . $quiz->getDescription() . "</textarea>
                        </div>
                        <div class='form-group'>
                            <label for='opento'>Open to (only one currently)</label>
                            <select name='opento' class='form-control'>
                                <option value='all'>All</option>
                            </select>
                        </div>
                        <input type='submit' value='Save' class='btn btn-primary form-control'>
                    </form>";

        return $html;
    }

    public function getAddQuizPage() {
        $html = "This will require a lot of things. Hold on for a long while until I fix this.";
        $html .= $this->getQuizForm(new QuizModel);

        return $html;
    }

    public function getRemoveQuizPage(QuizModel $quiz) {
        $html = "";
        $html .= "
                    <form method='post'>
                        <p>Are you totally sure that you want to delete this thing? It can't be undone and it will erase everything associated with that thing.</p>
                        <input type='hidden' name='totallysure' value='true'>
                        <input type='submit' value='Delete' class='btn btn-danger'>
                    </form>";

        return $html;
    }

    public function getAddQuestionPage(QuizModel $quiz) {
        $html = "<h3>You are now in the add question page</h3>";

        if ($quiz->getQuestionCount()) {
            $html .= "<p class=''>The other questions in the quiz:</p>";
            /** @var QuestionModel $question */
            $html .= "
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>Methods</th>
                                    <th>Question name</th>
                                </tr>
                            </thead>
                            <tbody>";
            foreach ($quiz->getQuestions() as $question) {

                $html .= "<tr>";
                $html .= "<td>" . $this->text($question->getQuestionText()) . "</td>";
                $html .= "<td class=''>" . $this->getEditButton('Edit', '/' . $quiz->getId() . '/' . $question->getId(),
                                                                'btn-default btn-xs');
                $html .= $this->getRemoveButton("Delete", "/" . $quiz->getId() . "/" . $question->getId(),
                                                "btn-danger btn-xs") . "</td>";

                $html .= "</tr>";
            }

            $html .= "</tbody></table>";
        } else {
            $html .= "<p class=''>There seems to be no other questions in this quiz yet.</p>";
        }

        $html .= $this->getQuestionForm(new QUestionModel);

        return $html;
    }

    public function getQuestionForm($question) {
        $html = "
                <form method='post'>
                    <div class='form-group'>
                        <label for='questiontext'>Question text</label>
                        <textarea name='questiontext' class='form-control'>" . $question->getQuestionText() . "</textarea>
                    </div>
                    <input type='submit' value='Save' class='btn btn-primary'>
                </form>";

        return $html;
    }

    public function getEditQuestionPage(QuizModel $quiz, QuestionModel $question) {
        $html = "";
        $html .= $this->getAddButton("Add answers", "/{$quiz->getId()}/{$question->getId()}", "btn-default");
        /** @var AnswerModel $answer */
        if ($question->getAnswers()) {
            $html .= "
                <table class='table'>
                <thead>
                    <tr>
                        <th>Answer text</th>
                        <th>Is correct?</th>
                        <th>Methods</th>
                    </tr>
                </thead>
                <tbody>


            ";
            $html .= $this->loopThroughAnswers($question->getAnswers(), $quiz->getId(), $question->getId());

            $html .= "</tbody>
                </table>";
        }
        $html .= $this->getQuestionForm($question);


        return $html;
    }

    private function loopThroughAnswers($answers, $quizid, $questionid) {
        $html = "";
        foreach ($answers as $answer) {
            if ($answer->getIscorrect()) {
                $iscorrect = "Yes";
            } else {
                $iscorrect = "No";
            }
            $html .= "<tr><td>" . $this->text($answer->getAnswertext()) . "</td>";
            $html .= "<td>" . $iscorrect . "</td>";
            $html .= "<td>" . $this->getEditButton('Edit',
                                                   "/" . $quizid . '/' .
                                                   $questionid . "/" .
                                                   $answer->getId(),
                                                   'btn-default btn-xs');
            $html .= $this->getRemoveButton('Remove',
                                            "/" . $quizid . '/' .
                                            $questionid . "/" .
                                            $answer->getId(),
                                            'btn-danger btn-xs') . "</td>";
            $html .= "</tr>";
        }
        return $html;
    }

    public function getAddAnswerPage(QuizModel $quiz, QuestionModel $question) {
        $html = "You are now in the add answer page";
        $html .= $this->getEditAnswerPage($quiz, $question, new AnswerModel());

        return $html;
    }

    public function getEditAnswerPage(QuizModel $quiz, QuestionModel $question, AnswerModel $answer) {
        $html = "You are now in the Answer page";
        $html .= $this->getAnswerForm($answer);

        $html .= "
            <table class='table'>
                <thead>
                    <tr>
                        <th>Answer text</th>
                        <th>Is correct?</th>
                        <th>Methods</th>
                    </tr>
                </thead>
                <tbody>
                ";
        $html .= $this->loopThroughAnswers($question->getAnswers(), $quiz->getId(), $question->getId());
        $html .= "
                </tbody>
                </table>";

        return $html;
    }

    public function getAnswerForm($answer) {
        $html = "
            <form method='post'>
                <div class='form-group'>
                    <label for='answertext'>Answer text</label>
                    <textarea name='answertext' class='form-control'>{$answer->getAnswertext()}</textarea>
                </div>

                <div class='form-group'>
                    <!-- HTML please -->
                    <input type='hidden' name='iscorrect' value='off'>
                    <label for='iscorrect'>
                        <input type='checkbox' name='iscorrect' " . ($answer->getIscorrect() == 1 ? "checked" : "") . " class='checkbox'>Is this answer correct?
                    </label>
                </div>
                <input type='submit' value='Save' class='btn btn-primary'>
            </form>";

        return $html;
    }

    public function getCUDPage() {
        $html = "Hello this is the CUD page, what can I do for you?";
        $html .= $this->getQuizesPage(true);

        return $html;
    }

    public function getQuizesPage($editMethods = false, $quizes = null, $message = true) {
        if ($quizes === null) {
            $quizes = $this->quizes;
        }
        if ($message) {
            $html = '<h3>Hello there. This is the quiz page. These are all the quizes available for you:</h3>';
        } else {
            $html = "";
        }
        if ($editMethods) {
            $html .= $this->getAddButton();
        }
        $html .= "<table class='table table-striped table-condensed'>
                    <thead>
                        <tr>
                            <th>Quiz name</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th>No. of Questions</th>
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
        foreach ($quizes as $quiz) {
            $descriptionText = StringHelper::shortenString($quiz->getDescription(), 30);
            /*if (mb_strlen($quiz->getDescription()) - 3 > 31) {
                $descriptionText = mb_substr($quiz->getDescription(), 0, 30) . "...";
            } else {
                $descriptionText = $quiz->getDescription();
            }*/
            $quizName = StringHelper::shortenString($quiz->getName(), 25);
            /*if (mb_strlen($quiz->getName()) - 3 > 26) {
                $quizName = mb_substr($quiz->getName(), 0, 25) . "...";
            } else {
                $quizName = $quiz->getName();
            }*/
            if (UserModel::getCurrentUser()->hasDoneQuiz($quiz->getId())) {
                $result = true;
                $hasDone = "Yes";
            } else {
                $result = false;
                $hasDone = "No";
            }

            $html .= "<tr>";
            $html .= "<td>" . $this->text($quizName) . "</td>" .
                "<td>" . $this->text($descriptionText) . "</td>" .
                "<td>" . $quiz->getCreated() . "</td>" .
                "<td>" . $quiz->getQuestionCount() . "</td>" .
                "<td>" . $hasDone . " " . ($result === true ? $this->getResultLink("Result",
                                                                                   "/" . $quiz->getId()) : "") . "</td>" .
                "<td>" . "<a href='/project/quizes/quiz/{$quiz->getId()}'>Go do this quiz!</a></td>";

            if ($editMethods) {
                $html .= "<td>" . $this->getEditButton('Edit', '/' . $quiz->getId() . '/',
                                                       'btn-default btn-xs') . $this->getRemoveButton("Delete",
                                                                                                      "/" . $quiz->getId() . "/",
                                                                                                      "btn-danger btn-xs") . "</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</tbody></table>";

        return $html;
    }

    public function getResultLink($text = "Remove quiz", $extra = "", $class = "") {
        return $this->getAnchor($this->rootAndMethod(QuizView::$resultMethodName) . $extra, $class, $text);
    }

    private function getAnchor($href, $class, $text) {
        return "<a href='$href' class='$class'>$text</a>";
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

    public function getResultPage($quiz, UserModel $getCurrentUser) {
        $html = "";
        if ($quiz) {
            $results = $getCurrentUser->getResults($quiz->getId());
            foreach ($results as $key => $result) {
                $resultarray = json_decode($result["result"], true);
                $html .= "<h4>Round #" . ($key + 1) . "</h4>";
                foreach ($resultarray as $key => $ra) {
                    if (gettype($key) === "string") {
                        continue;
                    }
                    if ($ra["onlyCorrect"]) {
                        $html .= "<p>You answered all questions correctly.</p>";
                    } else {
                        $html .= "<p>";
                        $html .= $ra["countRightAnswers"] . " right answers and " .
                            $ra["countWrongAnswers"] . " wrong answers out of " .
                            $ra["rightAnswerCount"] . " right answers and " .
                            $ra["wrongAnswerCount"] . " wrong answers.";
                        $html .= "</p>";
                    }
                }
            }
        }

        return $html;
    }

    public function getResultsPage($result, $quiz) {
        $html = "";
        $html .= "<h3>Result</h3>";
        echo "Current user";
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
}