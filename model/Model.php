<?php
defined("__ROOT__") or die("Noh!");
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:30
 */
/*
 * @todo: Implement the database connection
 */

class Model {
    protected static $PDO;

    public function __construct() {
    }

    static public function getConnection() {
        if (self::$PDO === null) {
            self::$PDO = new PDO(Settings::getSetting("db-dns"), Settings::getSetting("db-username"),
                                 Settings::getSetting("db-password"));
        }

        self::$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return self::$PDO;
    }
}