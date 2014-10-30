<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-30
 * Time: 12:47
 */
require_once("View.php");
require_once("QuizView.php");
require_once(__ROOT__ . "model/UserModel.php");

class UserView extends View {
    private $user;
    private $quizView;

    public function __construct(UserModel $user) {
        $this->user = $user;
        $this->quizView = new QuizView();
    }

    public function getUserPage() {
        if (!$this->user) {
            return "That user doesn't seem to exist";
        }
        $html = "";
        /** @var QuizModel $quiz */
        $html .= "Done quizes:";
        $html .= "<div class='panel panel-default'><div class='panel-body'>";
        $html .= "<table class='table'><thead><tr><th>Name</th><th>Done when</th><th></th></tr></thead><tbody>";
        foreach ($this->user->getDoneQuizes2() as $quiz) {
            $date = new DateTime($quiz->donewhen);

            $html .= "<tr>";
            $html .= "<td>" . $quiz->getName() . "</td>";
            $html .= "<td>" . $date->format("Y-m-d h:i:s") . "</td>";
            $html .= "<td>" . $this->quizView->getResultLink("Results", "/" . $quiz->getId(),
                                                             "btn btn-default") . "</td>";
            $html .= "</tr>";
        }
        $html .= "</tbody></div></div>";

        return $html;

    }
}