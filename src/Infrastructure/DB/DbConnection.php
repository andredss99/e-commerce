<?php

namespace Ecommerce\Infrastructure\DB;

use PDO;

class DbConnection {
    private const HOSTNAME = '172.19.0.2';
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

	private function setParams($statement, $parameters = array())
	{

		foreach ($parameters as $key => $value) {
			
			$this->bindParam($statement, $key, $value);

		}

	}

	private function bindParam($statement, $key, $value)
	{

		$statement->bindParam($key, $value);

	}

	/*public function query($rawQuery, $params = array())
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

	}*/

	public function select($rawQuery, $params = array()):array
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);

	}

    public function getConnection(): PDO
    {
        return $this->conn;
    }

}