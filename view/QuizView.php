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
    const EDIT_METHOD_NAME       = "edit";
    const ADD_METHOD_NAME        = "add";
    const REMOVE_METHOD_NAME     = "delete";
    const RESULT_METHOD_NAME     = "result";
    const TOTALLY_SURE           = "totallysure";
    const TOTALLY_SURE_VALUE     = "true";
    const QUESTION_TEXT_FORM     = "questiontext";
    const QUIZ_NAME_FORM         = "name";
    const QUIZ_DESCRIPTION_FORM  = "description";
    const QUIZ_OPENTO_FORM       = "opento";
    const ANSWER_ANSWERTEXT_FORM = "answertext";
    const ANSWER_ISCORRECT_FORM  = "isccorect";

    private $quizes;
    private $pd;

    public function __construct() {
        $this->pd = new Parsedown();
        $this->quizList = new QuizList();
        $this->quizes = $this->quizList->getAllQuizes();
        parent::__construct();
    }
    /*
     * Pages
     */
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

                $html .= "<div class='panel panel-default'>";
                $html .= "<div class='panel-body'>";
                /** @var AnswerModel $answer */
                $html .= "<h4>Question</h4>";
                $html .= $this->text($question->getQuestionText());
                $html .= "<hr>";
                $html .= "<h4>Answers</h4>";
                foreach ($question as $answer) {
                    $html .= "<div class='answer'>";
                    $html .= $this->text($answer->getAnswertext());

                    $html .= "<input type='checkbox' name='{$question->getId()}[]' value='{$answer->getId()}' class='checkbox' >";

                    $html .= "</div>";
                    $html .= "<hr>";
                }
                $html .= "</div>";
                $html .= "</div>";
            }
            $html .= "<input type='submit' value='Submit answers' class='btn btn-default'>";
            $html .= "</form>";
        } else {
            return $this->getQuizMissingPage();
        }

        return $html;
    }

    public function text($string) {
        return $this->pd->text(strip_tags($string));
    }

    public function getQuizMissingPage() {
        $html = "";
        $html .= "This quiz is not available.";

        return $html;
    }

    public function getEditQuizPage(QuizModel $quiz) {
        $html = "";
        $breadCrumbsRow = new BreadCrumbsRow($this->rootBase, "Home");
        $breadCrumbsRow2 = new BreadCrumbsRow($this->rootBase . "quizes/edit/", "Edit Quizes");
        $bcList = new BreadCrumbsRowList($breadCrumbsRow, $breadCrumbsRow2);
        $html .= BreadCrumbs::getBreadCrumbs($bcList, "Editing quiz '" . $quiz->getName() . "'");

        $html .= $this->getAddButton("Add questions", "/{$quiz->getId()}", "btn-default");

        $html .= $this->getQuizForm($quiz);
        /** @var QuestionModel $question */
        $html .= $this->loopThroughQuestions($quiz);

        return $html;
    }

    public function getAddButton($text = "Add quiz", $extra = "", $class = "btn-default") {
        return $this->getButton($this->rootAndMethod(QuizView::ADD_METHOD_NAME) . $extra, $class, $text);
    }

    private function getButton($href, $class, $text) {
        return "<a href='$href' class='btn $class'>$text</a>";
    }

    public function rootAndMethod($editMethod) {
        $result = $this->rootBase . "quizes/" . $editMethod;

        return $result;
    }

    public function getQuizForm($quiz) {
        /** @var QuizModel $quiz */
        $html = "
                    <form method='post'>
                        <div class='form-group'>
                            <label for='" . QuizView::QUIZ_NAME_FORM . "'>Quiz name</label>
                            <small>The name that the quiz will have. Must be atleast 5 characters long, no whitespaces allowed</small>
                            <input type='text' name='" . QuizView::QUIZ_NAME_FORM . "' class='form-control' value='" . $quiz->getName() . "' required pattern='^(.){5,}$' title='at least 5 letters'>
                        </div>
                        <div class='form-group'>
                            <label for='" . QuizView::QUIZ_DESCRIPTION_FORM . "'>Quiz description</label>
                            <small>The description of the quiz. Must be atleast 5 characters long, whitespaces allowed</small>
                            <textarea name='" . QuizView::QUIZ_DESCRIPTION_FORM . "' class='form-control' required pattern='([.\s]){5,}' title='at least 5 letters' rows=5>" . $quiz->getDescription() . "</textarea>
                        </div>
                        <div class='form-group'>
                            <label for='" . QuizView::QUIZ_OPENTO_FORM . "'>Open to (only one currently)</label>
                            <select name='" . QuizView::QUIZ_OPENTO_FORM . "' class='form-control' required>
                                <option value='all'>All</option>
                            </select>
                        </div>
                        <input type='submit' value='Save' class='btn btn-primary form-control'>
                    </form>";

        return $html;
    }

    public function loopThroughQuestions($quiz) {
        $html = "";
        /** @var QuizModel $quiz */
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
                                                "btn-danger btn-xs") . $this->getAddButton("Add answers", "/" .
                        $quiz->getId() . "/" .
                        $question->getId(),
                                                                                           "btn-success btn-xs") . "</td>";

                $html .= "</tr>";
            }
            $html .= "</tbody></table>";
        } else {
            $html .= "<p class=''>There seems to be no other questions in this quiz yet.</p>";
        }

        return $html;
    }

    public function getEditButton($text = "Edit quiz", $extra = "", $class = "btn-default") {
        return $this->getButton($this->rootAndMethod(QuizView::EDIT_METHOD_NAME) . $extra, $class, $text);
    }

    public function getRemoveButton($text = "Remove quiz", $extra = "", $class = "btn-danger") {
        return $this->getButton($this->rootAndMethod(QuizView::REMOVE_METHOD_NAME) . $extra, $class, $text);
    }

    public function getAddQuizPage() {
        $html = "";

        $html .= $this->getQuizForm(new QuizModel);

        return $html;
    }

    public function getRemoveQuizPage(QuizModel $quiz) {
        $html = "";
        $breadCrumbsRow = new BreadCrumbsRow($this->rootBase, "Home");
        $breadCrumbsRow2 = new BreadCrumbsRow($this->rootBase . "quizes/edit/", "Edit Quizes");
        $bcList = new BreadCrumbsRowList($breadCrumbsRow, $breadCrumbsRow2);

        $html .= BreadCrumbs::getBreadCrumbs($bcList, "Removing quiz '" . $quiz->getName() . "'");

        $html .= "
                    <form method='post'>
                        <p>Are you totally sure that you want to delete this quiz? It can't be undone and it will erase everything associated with that quiz.</p>
                        <input type='hidden' name='" . QuizView::TOTALLY_SURE . "' value='" . QuizView::TOTALLY_SURE_VALUE . "'>
                        <input type='submit' value='Delete' class='btn btn-danger'>
                    </form>";

        return $html;
    }

    public function getAddQuestionPage(QuizModel $quiz) {
        $html = "";
        $breadCrumbsRow = new BreadCrumbsRow($this->rootBase, "Home");
        $breadCrumbsRow2 = new BreadCrumbsRow($this->rootAndMethod(QuizView::ADD_METHOD_NAME) . "/", "Edit Quizes");
        $breadCrumbsRow3 = new BreadCrumbsRow($this->rootAndMethod(QuizView::ADD_METHOD_NAME) . "/" . $quiz->getId(),
                                              StringHelper::shortenString(strip_tags($quiz->getName()),
                                                                          10));
        $bcList = new BreadCrumbsRowList($breadCrumbsRow, $breadCrumbsRow2, $breadCrumbsRow3);
        $html .= BreadCrumbs::getBreadCrumbs($bcList, "Adding question");

        $html .= "<h3>You are now in the add question page</h3>";

        $html .= $this->getQuestionForm(new QuestionModel());

        $html .= $this->loopThroughQuestions($quiz);


        return $html;
    }

    /*
     * Helper functions
     */
    public function getQuestionForm($question) {
        /** @var QuestionModel $question */
        $html = "
                <form method='post'>
                    <div class='form-group'>
                        <label for='" . QuizView::QUESTION_TEXT_FORM . "'>Question text</label>
                        <small>The question text. Must be at least 5 characters long, whitespaces allowed. This input supports <a href='http://parsedown.org/demo'>Markdown</a>.</small>
                        <textarea name='" . QuizView::QUESTION_TEXT_FORM . "' class='form-control' required rows=5 pattern='^([.\s]){5,}$' title='at least 5 letters'>" . $question->getQuestionText() . "</textarea>
                    </div>
                    <input type='submit' value='Save' class='btn btn-primary form-control'>
                </form>";

        return $html;
    }

    public function getEditQuestionPage(QuizModel $quiz, QuestionModel $question) {
        $html = "";
        $breadCrumbsRow = new BreadCrumbsRow($this->rootBase, "Home");
        $breadCrumbsRow2 = new BreadCrumbsRow($this->rootAndMethod(QuizView::EDIT_METHOD_NAME) . "/", "Edit Quizes");
        $breadCrumbsRow3 = new BreadCrumbsRow($this->rootAndMethod(QuizView::EDIT_METHOD_NAME) . "/" . $quiz->getId(),
                                              StringHelper::shortenString(strip_tags($quiz->getName()),
                                                                          10));
        $bcList = new BreadCrumbsRowList($breadCrumbsRow, $breadCrumbsRow2, $breadCrumbsRow3);
        $html .= BreadCrumbs::getBreadCrumbs($bcList,
                                             "Editing question: " . StringHelper::shortenString(strip_tags($question->getQuestionText()),
                                                                                                10));
        $html .= $this->getAddButton("Add answers", "/{$quiz->getId()}/{$question->getId()}", "btn-default");

        $html .= $this->getQuestionForm($question);
        /** @var AnswerModel $answer */

        $html .= $this->loopThroughAnswers($question->getAnswers(), $quiz->getId(), $question->getId());


        return $html;
    }

    private function loopThroughAnswers(AnswerList $answers, $quizid, $questionid) {
        $html = "";

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

        /** @var AnswerModel $answer */
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
        $html .= "</tbody>
                </table>";

        return $html;
    }

    public function getAddAnswerPage(QuizModel $quiz, QuestionModel $question) {
        $html = "";

        $breadCrumbsRow = new BreadCrumbsRow($this->rootBase, "Home");
        $breadCrumbsRow2 = new BreadCrumbsRow($this->rootAndMethod(QuizView::EDIT_METHOD_NAME) . "/",
                                              StringHelper::shortenString(strip_tags($quiz->getName()),
                                                                          10));
        $breadCrumbsRow3 =
            new BreadCrumbsRow($this->rootAndMethod(QuizView::EDIT_METHOD_NAME) . "/" . $quiz->getId() . "/" . $question->getId(),
                               StringHelper::shortenString(strip_tags($question->getQuestionText()),
                                                           10));
        $bcList = new BreadCrumbsRowList($breadCrumbsRow, $breadCrumbsRow2, $breadCrumbsRow3);
        $html .= BreadCrumbs::getBreadCrumbs($bcList, "Adding answer");

        $html .= $this->getEditAnswerPage($quiz, $question, new AnswerModel(), false);

        return $html;
    }

    public function getEditAnswerPage(QuizModel $quiz, QuestionModel $question, AnswerModel $answer, $breadcrumbs = true) {
        $html = "";
        if ($breadcrumbs) {
            $breadCrumbsRow = new BreadCrumbsRow($this->rootBase, "Home");
            $breadCrumbsRow2 = new BreadCrumbsRow($this->rootAndMethod(QuizView::EDIT_METHOD_NAME) . "/",
                                                  StringHelper::shortenString(strip_tags($quiz->getName()),
                                                                              10));
            $breadCrumbsRow3 =
                new BreadCrumbsRow($this->rootAndMethod(QuizView::EDIT_METHOD_NAME) . "/" . $quiz->getId() . "/" . $question->getId(),
                                   StringHelper::shortenString(strip_tags($question->getQuestionText()),
                                                               10));
            $bcList = new BreadCrumbsRowList($breadCrumbsRow, $breadCrumbsRow2, $breadCrumbsRow3);
            $html .= BreadCrumbs::getBreadCrumbs($bcList, "Editing answer");
        }
        $html .= "<h4>Question: </h4>" . $this->text($question->getQuestionText());
        $html .= $this->getAnswerForm($answer);

        $html .= $this->loopThroughAnswers($question->getAnswers(), $quiz->getId(), $question->getId());

        return $html;
    }

    public function getAnswerForm($answer) {
        $html = "";
        /** @var AnswerModel $answer */
        $html .= "
            <form method='post'>
                <div class='form-group'>
                    <label for='" . QuizView::ANSWER_ANSWERTEXT_FORM . "'>Answer text</label>
                    <small>The answer text. Must be at least 5 characters long, whitespace allowed. This input supports <a href='http://parsedown.org/demo'>Markdown</a>.</small>
                    <textarea name='" . QuizView::ANSWER_ANSWERTEXT_FORM . "' class='form-control' required pattern='^([.\s]){5,}$' title='at least 5 letters, whitespaces allowed'>{$answer->getAnswertext()}</textarea>
                </div>

                <div class='form-group'>
                    <!-- HTML please -->
                    <input type='hidden' name='" . QuizView::ANSWER_ISCORRECT_FORM_FORM . "' value='off'>
                    <label>
                        <input type='checkbox' name='" . QuizView::ANSWER_ISCORRECT_FORM_FORM . "' " . ($answer->getIscorrect() == 1 ? "checked" : "") . " class='checkbox'>Is this answer correct?
                    </label>
                </div>
                <input type='submit' value='Save' class='btn btn-primary form-control'>
            </form>";

        return $html;
    }

    public function getCUDPage() {
        $html = "";
        $html .= $this->getQuizesPage(true);

        return $html;
    }

    public function getQuizesPage($editMethods = false, QuizList $quizes = null, $message = true, $mostPopular = false, $addbutton = true) {
        if ($quizes === null && $editMethods) {
            $quizes = $this->quizList->getByCreator(UserModel::getCurrentUser()->getId());
        } else if ($quizes === null) {
            $quizes = $this->quizList->getAllQuizes();
        }
        if ($message) {
            $html = '<h3>Hello there. This is the quiz page. These are all the quizes available for you:</h3>';
        } else {
            $html = "";
        }

        if ($editMethods && $addbutton) {
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
        if ($mostPopular) {
            $html .= "<th>Done times</th>";
        }
        $html .= "
                        </tr>
                    </thead>
                    <tbody>";
        foreach ($quizes as $quiz) {
            $descriptionText = StringHelper::shortenString($quiz->getDescription(), 30);

            $quizName = StringHelper::shortenString($quiz->getName(), 25);

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
                "<td>" . "<a href='" . $this->rootBase . "quizes/quiz/{$quiz->getId()}'>Go do this quiz!</a></td>";

            if ($editMethods) {
                $html .= "<td>" . $this->getEditButton('Edit', '/' . $quiz->getId() . '/',
                                                       'btn-default btn-xs') . $this->getRemoveButton("Delete",
                                                                                                      "/" . $quiz->getId() . "/",
                                                                                                      "btn-danger btn-xs") . $this->getAddButton("Add questions",
                                                                                                                                                 "/" . $quiz->getId() . "/",
                                                                                                                                                 "btn-success btn-xs") . "</td>";
            }
            if ($mostPopular) {
                //Breaking the quiz by introducing cnt into the model only here
                //$html .= "<td>" . $quiz->cnt . "</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</tbody></table>";

        return $html;
    }

    public function getResultLink($text = "Get results", $extra = "", $class = "") {
        return $this->getAnchor($this->rootAndMethod(QuizView::RESULT_METHOD_NAME) . $extra, $class, $text);
    }

    private function getAnchor($href, $class, $text) {
        return "<a href='$href' class='$class'>$text</a>";
    }

    public function getResultPage($quiz, UserModel $getCurrentUser) {
        $html = "";
        if ($quiz) {
            /** @var QuizModel $quiz */
            $results = $getCurrentUser->getResults($quiz->getId());
            foreach ($results as $key => $result) {
                $resultarray = json_decode($result["result"], true);
                $html .= "<div class='panel panel-default'>";
                $html .= "<div class='panel-body'>";
                $html .= "<h4>Round #" . ($key + 1) . "</h4>";
                foreach ($resultarray as $key2 => $ra) {
                    if (gettype($key2) === "string") {
                        continue;
                    }
                    if ($ra["onlyCorrect"]) {
                        $html .= "<p class='bg-success'>Question number " . ($key2 + 1) . ": You answered completely correctly. ";
                        $html .= $ra["countRightAnswers"] . " right answers and " .
                            $ra["countWrongAnswers"] . " wrong answers out of " .
                            $ra["rightAnswerCount"] . " right answers with " .
                            $ra["wrongAnswerCount"] . " wrong answers.";
                        $html .= "</p>";
                    } else {
                        $html .= "<p class='bg-danger' style='width:auto'>Question number " . ($key2 + 1) . ": ";
                        $html .= $ra["countRightAnswers"] . " right answers and " .
                            $ra["countWrongAnswers"] . " wrong answers out of " .
                            $ra["rightAnswerCount"] . " right answers with " .
                            $ra["wrongAnswerCount"] . " wrong answers.";
                        $html .= "</p>";
                    }
                }
                $html .= "</div></div>";
            }
        }

        return $html;
    }

    public function getResultsPage($result, $quiz) {
        $html = "";
        $html .= "<h3>Result</h3>";
        foreach ($result as $key => $resultRow) {
            //Typesafety in PHP is, well, really bad, but I do not want the string keys, just the resultrows that contain the good info

            if (gettype($key) === "integer") {
                $questionCount = $key + 1;
                $html .= "<h4>Question $questionCount</h4>";
                $html .= "<p class='bg-primary'>" . $resultRow["countRightAnswers"];
                $html .= " out of " . $resultRow["rightAnswerCount"];
                if ($resultRow["countWrongAnswers"] > 0) {
                    $html .= " with " . $resultRow["countWrongAnswers"] . " extra wrong answers.";
                } else {
                    $html .= "!";
                }
            }
            if ($key === "allCorrect" && $resultRow["allCorrect"] === true) {
                $html .= "<p class='bg-success'>Wow you got all the questions correct!</p>";
            }
        }

        return $html;
    }

    public function getMostDone($getMostDone) {
        $html = "";

        $html .= "
            <table class='table'>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Done quizes</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($getMostDone as $gmd) {
            $html .= "<tr>" . "<td>" . $this->getAnchor($this->rootBase . "user/" . $gmd["username"], "",
                                                        $gmd["username"]) . "</td>";
            $html .= "<td>" . $gmd["cnt"] . "</td>" . "</tr>";
        }
        $html .= "</tbody></table>";

        return $html;
    }

    public function removeDisallowedTags($string, $allowedTags = "<b><p><strong><i><code>") {
        return strip_tags($this->stripAttributes($string), $allowedTags);
    }

    public function stripAttributes($string) {
        return preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i", '<$1$2>', $string);
    }

    public function getTotallySure() {
        return $_POST[QuizView::TOTALLY_SURE];
    }

    /*
     * Forms
     */

    public function getAddData() {
        return $_POST;
    }

    public function getEditData() {
        return $_POST;
    }

    public function getAnswerData() {
        return $_POST;
    }
}

class BreadCrumbsRow {
    private $link;
    private $name;

    public function __construct($link, $name) {
        $this->link = $link;
        $this->name = $name;
    }

    public function getRow() {
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }
}

class BreadCrumbsRowList implements Iterator {
    private $rows;

    public function __construct() {
        $this->rows = func_get_args();
    }

    public function rewind() {
        reset($this->rows);
    }

    public function current() {
        return current($this->rows);
    }

    public function key() {
        return key($this->rows);
    }

    public function next() {
        return next($this->rows);
    }

    public function valid() {
        $key = key($this->rows);
        $var = ($key !== null && $key !== false);

        return $var;
    }
}

class BreadCrumbs {
    public static function getBreadCrumbs(BreadCrumbsRowList $rowsList, $active) {
        $html = "";
        $html .= "<ol class='breadcrumb'>";
        foreach ($rowsList as $row) {
            $html .= "<li><a href='{$row->getLink()}'>{$row->getName()}</a></li>";
        }
        $html .= "<li class='active'>$active</li>";
        $html .= "</ol>";

        return $html;
    }
}