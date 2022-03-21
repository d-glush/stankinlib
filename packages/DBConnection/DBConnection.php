<?php

namespace Packages\DBConnection;

use PDO;

class DBConnection {

    static public ?PDO $connection = null;

    public function __construct() {
        if (DBConnection::$connection) {
            return;
        }
        $this->createConnection();
    }

    public function query(string $query): bool|\PDOStatement
    {
        return self::$connection->query($query);
    }

    public function execute(string $query): int|bool
    {
        return self::$connection->exec($query);
    }

    private function createConnection(): void
    {
        $config = (include $_SERVER['DOCUMENT_ROOT'] . '/core/config.php')['DBConfig'];
        $host = $config['host'];
        $dbName = $config['database'];
        $user = $config['user'];
        $password = $config['password'];
        $driver = "mysql:host=$host;dbname=$dbName";
        self::$connection = new PDO($driver, $user, $password);
    }

    public function getLastInsertId(): int
    {
        return self::$connection->lastInsertId();
    }
}