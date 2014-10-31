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

        $html .= "<div class='panel panel-default'>";
        $html .= "<div class='panel-heading'><h4 class='panel-title'>Created quizes:</h4></div>";
        $html .= "<div class='panel-body'>All the quizes that the user has created will be listed here.</div>";

        $html .= $this->quizView->getQuizesPage($this->currentUserSameAsTargetUser(), $this->user->getCreatedQuizes(),
                                                false, false, false);

        $html .= "</div>";

        $html .= "<div class='panel panel-default'>";
        $html .= "<div class='panel-heading'><h4 class='panel-title'>Done quizes:</h4></div>";
        $html .= "<div class='panel-body'>Here all the quizes that the user has done will be listed.</div>";
        $html .= "
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Done when</th>
                                ";
        if ($this->currentUserSameAsTargetUser()) {
            $html .= "
                                <th></th>
                                ";
        }
        $html .= "
                            </tr>
                        </thead>
                        <tbody>";

        foreach ($this->user->getDoneQuizes() as $quiz) {
            $date = new DateTime($quiz->donewhen);

            $html .= "<tr>";
            $html .= "<td>" . $quiz->getName() . "</td>";
            $html .= "<td>" . $date->format("Y-m-d h:i:s") . "</td>";
            if ($this->currentUserSameAsTargetUser()) {
                $html .= "<td>" . $this->quizView->getResultLink("Results", "/" . $quiz->getId(),
                                                                 "btn btn-default") . "</td>";
            }
            $html .= "</tr>";
        }

        $html .= "</tbody></div>";

        return $html;

    }

    public function currentUserSameAsTargetUser() {
        return $this->user->getId() === UserModel::getCurrentUser()->getId();
    }
}