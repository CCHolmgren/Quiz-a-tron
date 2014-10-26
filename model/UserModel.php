<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-08
 * Time: 11:51
 */
require_once("Model.php");

/*
 * Represents a user, with all of its might (not right now)
 * Should contain everything that a user has, such as username, password, quizes done and quizes left
 * Everything that you would need to check and handle stuff for the user
 */

class UserModel extends Model {
    private $id;
    private $errors;
    private $username;
    private $password;
    private $quizes;
    private $email;

    public function __construct() {
        parent::__construct();
        $this->quizes = QuizModel::getDoneQuizes($this->id);
    }

    /**
     * Returns the current user that is stored in the userid of the session variable, or returns a new UserModel
     * That usermodel will have everything as null and you must make sure to check if the user id logged in first
     * @return UserModel
     */
    static public function getCurrentUser() {
        if (isset($_SESSION["userid"])) {
            return self::getUserById($_SESSION["userid"]);
        }

        return new UserModel();
    }

    /**
     * @param $id
     * @return UserModel
     */
    static public function getUserById($id) {
        $conn = self::getConnection();
        try {
            $sth = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $sth->execute(array($id));
            $user = $sth->fetchObject("UserModel");

            return $user;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public static function isLoggedIn() {
        return isset($_SESSION["loggedin"]);
    }

    public function hasDoneQuiz($quizid) {
        $conn = $this->getConnection();
        $sth = $conn->prepare("SELECT 1 FROM donequizes WHERE userid = ? AND quizid = ?");
        $sth->execute(array($this->getId(), $quizid));
        if ($sth->fetch()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * This is to inorporate with a AnonymosUserModel, but it doesn't exist yet and as such has no real use
     * @return bool
     */
    public function isAnonymous() {
        return false;
    }

    /**
     * @param $username string Username of the user we want to register. This will result in an
     * exception if the username already exists in the database
     * @param $password string Password of the user we want to register
     * @param $email string Email of the user we want to register. This will result in na
     * exception if the email already exists in the database
     * @return UserModel The information of the newly registered user
     * @throws Exception If the username or email already exists or if there is some other exception
     */
    public function registerUser($username, $password, $email) {
        //Get the connection and register the user
        //Return this which will be the new user, or throw an exception if it failed
        $conn = $this->getConnection();
        try {
            $sth = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?,?,?)");
            $sth->execute(array($username, password_hash($password, PASSWORD_DEFAULT), $email));
        } catch (PDOException $e) {
            if ($e->getCode() === "23505") {
                throw new Exception("That username or email already exists, please try another one");
            }
        }
        try {
            $sth = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $sth->execute(array($username));
            $usermodel = $sth->fetchObject("UserModel");
        } catch (Exception $e) {
            throw $e;
        }

        return $usermodel;
        //throw new NotImplementedException("registerUser is not implemented yet and as such will not have done anything");
    }

    /**
     * @param array $data Data to validate. This is assumed to have a username, password, repeatedpassword and email field
     * If it doesn't it will fail. Please do not let it
     * @return array|bool Returns either the errors associated iwth the validation or a true if the validation succeeded
     * Please use PHPs === instead of just == as it will allow $errors to be treated as true eventhough it shouldn't
     */
    public function validateInput(array $data) {
        $username = $data["username"];
        $password = $data["password"];
        $repeatedpassword = $data["repeatedpassword"];
        $email = $data["email"];
        $errors = array();
        if (mb_strlen($username) < 6) {
            $errors[] = "The username can't be less than 6 letters long";
        }
        /*if ($username !== filter_input(FILTER_SANITIZE_FULL_SPECIAL_CHARS, $username)) {
            $errors[] = "The username can't contain other symbols than a-z, A-Z, 0-9";
        }*/
        if ($this->usernameExists($username)) {
            $errors[] = "The username already exists.";
        }
        /*if ($email !== filter_input(FILTER_VALIDATE_EMAIL, $email)) {
            $errors[] = "The email isn't really an email";
        }*/
        if ($this->emailExists($email)) {
            $errors[] = "The email already exists.";
        }
        if ($password !== $repeatedpassword) {
            $errors[] = "The two passwords do not match.";
        }
        if (count($errors)) {
            return $errors;
        }

        return true;
    }

    /**
     * @param $username string The username that we want to check if it exists in the database
     * @return bool
     */
    public function usernameExists($username) {
        $conn = $this->getConnection();

        $sth = $conn->prepare("SELECT 1 FROM users WHERE username = ?");
        $sth->execute(array($username));

        if ($sth->fetch()) {
            return true;
        }

        return false;
    }

    /**
     * @param $email string The Email that we want to check if it exists in the database
     * @return bool true if it does, false if it doesn't
     * @throws Exception If the database fails in any way, lots of things to go wrong
     */
    public function emailExists($email) {
        try {
            $conn = $this->getConnection();
        } catch (Exception $e) {
            throw new Exception("The connection to the database failed in " . __FILE__);
        }

        try {
            $sth = $conn->prepare("SELECT 1 FROM users WHERE email = ?");
            $sth->execute(array($email));
        } catch (Exception $e) {
            throw new Exception("The checking of the user with email " . $email . " failed in " . __FILE__);
        }

        if ($sth->fetch()) {
            return true;
        }

        return false;
    }

    /**
     * Logs the user in if the user exists in the database with the username and password field
     * @param array $data Assumed to contain a username and a password field that represents the users username and password
     * @return bool true if it succeeded false if it doesn't
     * @throws Exception Uses userExists which throws exceptions
     */
    public function validateLogin(array $data) {
        if ($this->userExists($data["username"], $data["password"])) {
            session_regenerate_id(true);
            $_SESSION["loggedin"] = true;
            $_SESSION["userid"] = $this->id;
            $_SESSION["username"] = $this->username;

            return true;
        } else {
            return false;
        }
    }

    public function userExists($username, $password) {
        try {
            $conn = $this->getConnection();
        } catch (Exception $e) {
            throw new Exception("The connection to the database failed in " . __FILE__);
        }
        try {
            $sth = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $sth->execute(array($username));
        } catch (Exception $e) {
            throw new Exception("The selection of the user with username " . $username . " failed in " . __FILE__);
        }
        $user = false;
        try {
            $user = $sth->fetchObject("UserModel");
        } catch (Exception $e) {
            throw new Exception("The getting of the user with username " . $username . " failed in " . __FILE__);
        }

        if ($user !== false) {
            $this->id = $user->getId();
            $this->username = $user->getUsername();
            $this->email = $user->email;

            if (password_verify($password, $user->getPassword())) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function getResults($quizid) {
        $conn = $this->getConnection();
        $sth = $conn->prepare("SELECT * FROM donequizes WHERE userid = ? AND quizid = ?");
        $sth->execute(array($this->getId(), $quizid));

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDoneQuizes() {
        $conn = $this->getConnection();
        $sth =
            $conn->prepare("SELECT quiz.* FROM quiz, donequizes WHERE quiz.id = donequizes.quizid AND donequizes.userid = ?");
        $sth->execute(array($this->id));
        $result = [];
        while ($row = $sth->fetchObject("QuizModel")) {
            $result[] = $row;
        }

        return $result;
    }
    public function logout() {
        session_destroy();
        session_regenerate_id(true);
    }

    public function getErrors() {
        return $this->errors;
    }

    /**
     * @return null|string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @return null|string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @return null|string
     */
    public function getEmail() {
        return $this->email;
    }
}