<?php
namespace src\br\com\caelum\leilao\Factory;

use PDO;

class ConnectionFactory
{
    private static $host = "localhost";
    private static $root = "root";
    private static $root_password = "caelum";
    private static $db = "leilao";
    
    public static function getConnectionWithoutDb(){
        return new PDO("mysql:host=".static::$host, static::$root, static::$root_password);
    }
    
    public static function getConnection(){
        return new PDO("mysql:host=".static::$host.";dbname=".static::$db, static::$root, static::$root_password);
    }
    
    public static function getDatabase(){
        return static::$db;
    }
}

