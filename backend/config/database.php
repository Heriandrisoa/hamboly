<?php
class Database
{
    // ici l'instance de pdo est un singleton 
    private static ?PDO $pdo = null;

    public static function connect(): PDO
    {
        if (self::$pdo !== null)
            return self::$pdo;
        
        $host = "localhost";
        $port = "5432";
        $dbname = "hamboly";
        $user = "kukuna";
        $password = "mamanlah";

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

        // init pdo
        self::$pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        return self::$pdo;
    }
}