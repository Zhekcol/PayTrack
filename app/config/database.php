<?php
class Database {

    private static $pdo = null;

    public static function getConnection() {

        if (self::$pdo === null) {
            $host = "localhost";
            $dbname = "paytrack";
            $user = "root";
            $pass = "";

            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}

