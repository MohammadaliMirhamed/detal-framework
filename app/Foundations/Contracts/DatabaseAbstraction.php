<?php

namespace App\Foundations\Contracts;

use PDO;
use Exception;

abstract class DatabaseAbstraction extends PDO
{
    /**
	 * Init database connection
	 */
	public function __construct()
	{
        $DB_TYPE = getenv('DB_CONNECTION');
        $DB_HOST = getenv('DB_HOST');
        $DB_PORT = getenv('DB_PORT');
        $DB_NAME = getenv('DB_DATABASE');
        $DB_USER = getenv('DB_USERNAME');
        $DB_PASS = getenv('DB_PASSWORD');
        $DB_CHAR = getenv('DB_CHARSET');

		parent::__construct($DB_TYPE.':host='.$DB_HOST.';port='.$DB_PORT.';dbname='.$DB_NAME.';charset='.$DB_CHAR, $DB_USER, $DB_PASS);
	}

    /**
     * Run raw sql query
     * 
     * @param string $sql
     * @param array $params
     * @param int $fetchMode
     * @return array
     */
    public function raw(string $sql, array $params = [], int $fetchMode = PDO::FETCH_ASSOC): array
    {
        $sth = $this->prepare($sql);
		foreach ($params as $key => $value)
			$sth->bindValue(":$key", $value);
		
		if (!$sth->execute()) {
            $this->handleError();
        } else {
            return $sth->fetchAll($fetchMode);
        }
    }

    /**
	 * error check
	 * 
	 * @return void
	 */
	public function handleError() :void
	{
		if ($this->errorCode() != '00000') {
			if ($this->_errorLog == true) {
                echo json_encode($this->errorInfo());
                throw new Exception("Error: " . implode(',', $this->errorInfo()));
            }
		}
	}

    /**
     * select recored from database
     * 
	 * @param string $whereCluses
	 * @param array $whereParams
     * @param int $fetchMode
     * @param int $limit
     * @return mixed
     */
    abstract public function select(string $whereCluses = '', array $whereParams = [], string $orderBy = '', int $limit = 0): mixed;


    /**
     * Insert recored to database
     * 
     * @param array $data
     * @return mixed
     */
    abstract public function insert(array $data): mixed;

    /**
     * Update recored in database
     * 
     * @param array $data
     * @param string $whereCluses
	 * @param array $whereParams
     * @return bool
     */
    abstract function update(array $data, string $whereCluses = '', array $whereParams = []): bool;


    /**
     * Delete recored from database
     * 
     * @param string $whereCluses
	 * @param array $whereParams
     * @return int|bool
     */
	abstract public function delete(string $whereCluses = '', array $whereParams = []): int|bool;

    /**
     * Get rows count
	 * @param string $whereCluses
	 * @param array $whereParams
     * @return int
     */
    abstract public function rowsCount(string $whereCluses = '', array $whereParams = []): int;

}