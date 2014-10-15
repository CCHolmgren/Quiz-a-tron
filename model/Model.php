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

    public function __construct()
    {
    }

    static public function getConnection()
    {
        $PDO = new PDO("pgsql:host=localhost;dbname=project;", "php", "password");
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $PDO;
    }
}