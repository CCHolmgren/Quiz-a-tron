<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-31
 * Time: 11:30
 */
require_once("Model.php");

class DoneQuizModel extends Model {
    private $id;
    private $quizid;
    private $userid;
    private $answers;
    private $donewhen;
    private $result;

    static public function getDoneQuizes($userid) {
        $conn = self::getConnection();
        $sth = $conn->prepare("SELECT * FROM donequizes WHERE userid = ?");
        $sth->execute(array($userid));

        $result = [];
        while ($row = $sth->fetchObject("DoneQuizModel")) {
            $result[] = $row;
        }

        return $result;
    }
}

class DoneQuizesList implements Iterator {
    private $donequizes;

    public function __construct(array $quizes) {
        $this->donequizes = $quizes;
    }

    public static function getDoneQuizesByUserId($id) {
        return new DoneQuizesList(DoneQuizModel::getDoneQuizes($id));
    }

    public function rewind() {
        reset($this->donequizes);
    }

    /**
     * @return AnswerModel
     */
    public function current() {
        return current($this->donequizes);
    }

    /**
     * @return Integer
     */
    public function key() {
        return key($this->donequizes);
    }

    /**
     * @return AnswerModel
     */
    public function next() {
        return next($this->donequizes);
    }

    /**
     * @return bool
     */
    public function valid() {
        $key = key($this->donequizes);
        $var = ($key !== null && $key !== false);

        return $var;
    }
}