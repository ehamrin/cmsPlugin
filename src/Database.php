<?php

class Database
{
    private static $dsn = \Settings::DatabaseDSN;
    private static $database = \Settings::DatabaseDatabase;
    private static $username= \Settings::DatabaseUsername;
    private static $password = \Settings::DatabasePassword;

    private static $connection = null;


    public static function GetConnection()
    {
        return self::$connection ?? self::CreateConnection();
    }

    private static function CreateConnection(){
        self::$connection =  new \PDO('mysql:host=' . self::$dsn . ';dbname=' . self::$database . ';', self::$username, self::$password, array(\PDO::FETCH_OBJ));
        self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return self::$connection;
    }
}