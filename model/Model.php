<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-06
 * Time: 21:30
 */
/*
 * @todo: Implement the database connection
 */
class Model{
    protected static $PDO;

    public function __construct()
    {
    }

    static public function getConnection()
    {
        if (self::$PDO === null)
            self::$PDO = new PDO("pgsql:host=localhost;dbname=project;", "php", "password");

        self::$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$PDO;
    }
}