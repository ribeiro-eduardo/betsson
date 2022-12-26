<?php

namespace App\DAO;
require __DIR__  . '/../../env.php';

abstract class Connection
{
    /**
     * @var \PDO
     */
    public static $pdo;

    // Singleton Design Pattern
    private function __construct() {}

    public function __destruct()
    {
        self::$pdo = null;
    }

    public static function getPdo()
    {
        if (!isset(self::$pdo)) {
            $host   = getenv('MYSQL_HOST');
            $dbname = getenv('MYSQL_DBNAME');
            $user   = getenv('MYSQL_USER');
            $pass   = getenv('MYSQL_PASSWORD');
            $port   = getenv('MYSQL_PORT');
    
            $dsn = "mysql:host={$host};dbname={$dbname};port={$port}";
            
            // db static instance receives PDO object
            self::$pdo = new \PDO($dsn, $user, $pass);
            
            self::$pdo->setAttribute(
                \PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION
            );

            self::$pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
        }

        return self::$pdo;
    }
}