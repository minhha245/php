<?php
class DB
{
    private static $instance = null;
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new PDO('mysql:host=' . LOCALHOST . ';dbname=' . DBNAME, USERNAME, PASSWORD);
                self::$instance->exec("SET NAMES 'utf8'");
            } catch (PDOException $ex) {
                die($ex->getMessage());
                echo "Error";
            }
        }
        return self::$instance;
    }
}
