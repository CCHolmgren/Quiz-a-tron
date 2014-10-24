<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 12:59
 */
require_once("Model.php");

class QuizModel extends Model {
    public $description = "Default description";
    private $id;
    private $name;
    private $creator;
    private $questions;
    private $opento;
    private $userWhoCreated;

    public function __construct($name = null, $description = null, $opento = null) {
        if ($name !== null) {
            $this->name = $name;
        }
        if ($description !== null) {
            $this->description = $description;
        }
        if ($opento !== null) {
            $this->opento = $opento;
        }

        $this->loadQuestions();
        $this->userWhoCreated = UserModel::getUserById($this->creator);
    }

    public function loadQuestions() {
        if ($this->id) {
            $conn = $this->getConnection();
            $sth = $conn->prepare("SELECT * FROM questions WHERE quizid = ?");
            $sth->execute(array($this->id));
            while ($object = $sth->fetchObject("QuestionModel")) {
                $this->questions[] = $object;
            }
        }
    }

    static public function getAllQuizes() {
        $conn = self::getConnection();
        $sth = $conn->prepare("SELECT * FROM quiz WHERE visible = 1");
        $sth->execute();
        $quizes = array();
        while ($object = $sth->fetchObject("QuizModel")) {
            $quizes[] = $object;
        }

        return $quizes;
    }

    static public function getDoneQuizes($userid) {
        $conn = self::getConnection();
        $sth = $conn->prepare("SELECT * FROM donequizes WHERE userid = ?");
        $sth->execute(array($userid));
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * @todo: Implement this function properly
     * @param array $data
     * @return array
     */
    public function validateAnswers(array $data) {
        $result = array();
        foreach ($data as $key => $dataRow) {

            $question = $this->getQuestionById($key);
            if ($question) {
                $result[] = $question->validateAnswers($dataRow);
            } else {
                $result[] = ["What the fuck"];
            }

        }
        /** @var QuestionModel $question */
        foreach ($this->questions as $key => $question) {
            echo "Key";
            var_dump($key);
            echo "QUestion";
            var_dump($question);
            if (isset($data[$question->getId()])) {
                $result[] = $question->validateAnswers($data[$question->getId()]);
            } else {
                $result[] = $question->validateAnswers(array());
            }
        }
        //foreach ($data as $key => $dataGroup) {
        /** @var QuestionModel $question */
        //    $question = $this->questions[$key - 1];
        //    $result[] = $question->validateAnswers($dataGroup);
        //}
        $allCorrect = 0;
        foreach ($result as $questionResult) {
            if ($questionResult["onlyCorrect"] === true) {
                $allCorrect += 1;
            }
        }
        //This checks if all questions are correctly answered
        if ($allCorrect === count($result)) {
            $result["allCorrect"] = true;
        } else {
            $result["allCorrect"] = false;
        }
        $this->saveAnswers($data, $result);

        return $result;
    }

    /**
     * @param $id
     * @return null|QuestionModel
     */
    public function getQuestionById($id) {
        /** @var QuestionModel $question */
        foreach ($this->getQuestions() as $question) {
            if ($question->getId() == $id) {
                return $question;
            }
        }

        return null;
    }

    /**
     * @return array QuestionModel
     */
    public function getQuestions() {
        return $this->questions;
    }

    public function saveAnswers(array $data, array $result) {
        $conn = $this->getConnection();
        $sth = $conn->prepare("INSERT INTO donequizes(quizid, userid, donewhen, answers, result) VALUES(?,?,?,?, ?)");
        $sth->execute(array($this->id, UserModel::getCurrentUser()->getId(), date("Y-m-d h:i:s",
                                                                                  time()), json_encode($data), json_encode($result)));

        return true;
    }

    public function saveQuiz(array $questions) {
        $conn = $this->getConnection();
        $sth = $conn->prepare("INSERT INTO quiz(creator, name, opento, description) VALUES (?,?,?,?) RETURNING id");
        $sth->execute(array(UserModel::getCurrentUser()->getId(), $this->name, "all", $this->description));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        $this->id = $result["id"];

        /** @var QuestionModel $question */
        foreach ($questions as $question) {
            $question->saveQuestion($this->id);
        }

    }

    public function addQuiz() {
        $conn = $this->getConnection();
        $sth = $conn->prepare("INSERT INTO quiz(creator, name, opento, description) VALUES (?,?,?,?) RETURNING id");
        $sth->execute(array($this->creator, $this->name, $this->opento, $this->description));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        $this->id = $result["id"];
    }

    public function editQuiz(array $editData) {
        foreach ($editData as $key => $value) {
            if ($this->$key !== $value) {
                $this->$key = $value;
            }
        }
        $this->updateQuiz();
    }

    public function updateQuiz() {
        $conn = $this->getConnection();
        $sth = $conn->prepare("UPDATE quiz SET (creator, name, opento, description) = (?,?,?,?) WHERE id = ?");
        $sth->execute(array($this->creator, $this->name, $this->opento, $this->description, $this->getId()));
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    public function removeQuiz() {
        $conn = $this->getConnection();
        $sth = $conn->prepare("UPDATE quiz SET visible = 0 WHERE id = ?");
        $sth->execute(array($this->getId()));
    }


    /**
     * @return null
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param null $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($quiztext) {
        $this->description = $quiztext;
    }

    /**
     * @return null
     */
    public function getOpento() {
        return $this->opento;
    }

    /**
     * @param null $opento
     */
    public function setOpento($opento) {
        $this->opento = $opento;
    }

    /**
     * @return mixed
     */
    public function getCreator() {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator) {
        $this->creator = $creator;
    }

    public function getQuestionCount() {
        return count($this->questions);
    }
}