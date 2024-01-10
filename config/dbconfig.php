<?php

class Database
{
    private static $dbHost = "localhost";
    private static $dbName = "serveetudant_2";
    private static $dbUser = "root";
    private static $dbUserPassword = "";
    private static $connection = null;

    public static function connect()
    {
        try {
            self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName . ';port=3306', self::$dbUser, self::$dbUserPassword);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return self::$connection;
    }

    public static function disconnect()
    {
        self::$connection = null;
    }
}

// Utilisez la méthode connect de manière statique
Database::connect();

// ...

// N'oubliez pas de fermer la connexion à un moment donné
Database::disconnect();
?>
