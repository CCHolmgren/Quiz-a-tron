<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 12:59
 */
require_once("Model.php");

class QuizModel extends Model{
    public $description = "Default description";
    private $id;
    private $name;
    private $creator;
    private $questions;
    private $opento;

    public function __construct(){
        $this->loadQuestions();
    }

    public function loadQuestions()
    {
        $conn = $this->getConnection();
        $sth = $conn->prepare("SELECT * FROM questions WHERE quizid = ?");
        $sth->execute(array($this->id));
        while ($object = $sth->fetchObject("QuestionModel")) {
            $this->questions[] = $object;
        }
    }

    static public function getAllQuizes()
    {
        $conn = self::getConnection();
        $sth = $conn->prepare("SELECT * FROM quiz");
        $sth->execute();
        $quizes = array();
        while ($object = $sth->fetchObject("QuizModel")) {
            $quizes[] = $object;
        }
        return $quizes;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @todo: Implement this function properly
     * @param array $data
     * @return array
     */
    public function validateAnswers(array $data)
    {
        foreach ($data as $key => $dataGroup) {
            $this->questions[$key - 1]->validateAnswers($dataGroup);
            var_dump($key, $dataGroup);
        }
        $this->saveAnswers($data);
        return $data;
    }

    public function saveAnswers(array $data)
    {
        $conn = $this->getConnection();
        $sth = $conn->prepare("INSERT INTO donequizes(quizid, userid, donewhen, answers) VALUES(?,?,?,?)");
        $sth->execute(array($this->id, UserModel::getCurrentUser()->getId(), date("Y-m-d h:i:s", time()), json_encode($data)));
        return true;
    }

    public function getDoneQuizes($userid)
    {
        $conn = $this->getConnection();
        $sth = $conn->prepare("SELECT * FROM donequizes WHERE userid = ?");
        $sth->execute(array($userid));
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}