<?php

namespace Ecommerce\Infrastructure\DB;

use PDO;

class DbConnection
{
    private const HOSTNAME = 'db';
    private const USERNAME = 'andre';
    private const PASSWORD = 'password';
    private const DB_NAME = 'db_ecommerce';

    private PDO $conn;

    public function __construct()
    {
        $this->conn = new PDO(
            'mysql:dbname=' . self::DB_NAME . ';host=' . self::HOSTNAME,
            self::USERNAME,
            self::PASSWORD
        );
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }
}
