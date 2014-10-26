<?php

/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-26
 * Time: 18:31
 */
class Messages {
    public function saveMessage($message) {
        if (isset($_SESSION)) {
            $_SESSION[Settings::getSetting("MessagesLocation")][] = $message;
        }
    }

    public function getMessage() {
        if (isset($_SESSION[Settings::getSetting("MessagesLocation")])) {
            return array_pop($_SESSION[Settings::getSetting("MessagesLocation")]);
        }
    }

    public function getMessages() {
        if (isset($_SESSION[Settings::getSetting("MessagesLocation")])) {
            $messages = $_SESSION[Settings::getSetting("MessagesLocation")];
            unset($_SESSION[Settings::getSetting("MessagesLocation")]);

            return $messages;
        }
    }
}