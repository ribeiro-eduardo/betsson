<?php

namespace App\DAO;

abstract class Connection
{
    /**
     * @var \PDO
     */
    protected $pdo;

    public function __construct()
    {
        $host   = getenv('MYSQL_HOST');
        $dbname = getenv('MYSQL_DBNAME');
        $user   = getenv('MYSQL_USER');
        $pass   = getenv('MYSQL_PASSWORD');
        $port   = getenv('MYSQL_PORT');

        $dsn = "mysql:host={$host};dbname={$dbname};port={$port}";
        
        $this->pdo = new \PDO($dsn, $user, $pass);
        
        $this->pdo->setAttribute(
            \PDO::ATTR_ERRMODE,
            \PDO::ERRMODE_EXCEPTION
        );
    }
}