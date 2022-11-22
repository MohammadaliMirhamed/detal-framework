<?php

namespace App\Configs;

use PDO;
use App\Foundations\Contracts\DatabaseAbstraction;

class Database extends DatabaseAbstraction
{
	/**
	 * @var string $table
	 */
	protected $table = null;

	/**
	 * @var array $fields
	 */
	protected $fields = [];

	/**
	 * @var object $sthSelect
	 */
	private $sthSelect = null;
    
    /**
     * select recored from database
     * 
	 * @param string $whereCluses
	 * @param array $whereParams
     * @param int $fetchMode
     * @param int $limit
     * @return mixed
     */
	public function select(string $whereCluses = '', array $whereParams = [], string $orderBy = '', int $limit = 0): mixed
    {
		$fileds = $this->fields ? implode(',', $this->fields) : '*';

        $this->sthSelect = $this->prepare("SELECT $fileds from $this->table $whereCluses $orderBy" . ($limit > 0 ? " LIMIT $limit" : ''));

		foreach ($whereParams as $key => $value) {
			$this->sthSelect->bindValue(":$key", $value);
		}
		
		if (!$this->sthSelect->execute()) {
			$this->handleError();
		} else {
			return $this;
		}   
    }

	/**
     * Insert recored to database
     * 
     * @param array $data
     * @return mixed
     */
	public function insert(array $data): mixed
	{
		ksort($data);
		
		$fieldNames = implode('`, `', array_keys($data));
		$fieldValues = ':' . implode(', :', array_keys($data));
		
		$sth = $this->prepare("INSERT INTO $this->table (`$fieldNames`) VALUES ($fieldValues)");
		
		foreach ($data as $key => $value) {
			$sth->bindValue(":$key", $value);
		}
		
		if (!$sth->execute()) {
			$this->handleError();
			return $sth->errorInfo();
		}

		return  $this->lastInsertId();
	}
	
	/**
     * Update recored in database
     * 
     * @param array $data
     * @param string $whereCluses
	 * @param array $whereParams
     * @return bool
     */
	public function update(array $data, string $whereCluses = '', array $whereParams = []): bool
	{
		ksort($data);
		
		$fieldDetails = NULL;

		foreach($data as $key=> $value) {
			$fieldDetails .= "`$key` = :$key,";
		}

		$fieldDetails = rtrim($fieldDetails, ',');
		
		$sth = $this->prepare("UPDATE $this->table SET $fieldDetails $whereCluses");
		
		foreach ($data as $key => $value) {
			$sth->bindValue(":$key", $value);
		}

		foreach ($whereParams as $key => $value) {
			$sth->bindValue(":$key", $value);
		}
		
		return $sth->execute();
	}
	
	/**
     * Delete recored from database
     * 
     * @param string $whereCluses
	 * @param array $whereParams
     * @return int|bool
     */
	public function delete(string $whereCluses = '', array $whereParams = []): int|bool
	{
		$sth = $this->prepare("DELETE from $this->table $whereCluses");

		foreach ($whereParams as $key => $value) {
			$sth->bindValue(":$key", $value);
		}

		return $sth->execute();
	}
	
	/**
     * Get rows count
     * 
     * @param string $whereCluses
	 * @param array $whereParams
     * @return int
     */
    public function rowsCount(string $whereCluses = '', array $whereParams = []): int
	{
		$sth = $this->prepare("SELECT * from $this->table $whereCluses");

		foreach ($whereParams as $key => $value) {
			$sth->bindValue(":$key", $value);
		}

		$sth->execute();
        return $sth->rowCount(); 
    }

	/**
	 * Get the results as object
	 * 
	 * @return array
	 */
	public function get(): array
	{
		return json_decode(json_encode($this->toArray()));
	}

	/**
	 * Get first index of results
	 * 
	 * @param string $field
	 * @return mixed
	 */
	public function first(): mixed
	{	
		$rows = $this->toArray();
		return isset($rows[0]) ? json_decode(json_encode($rows[0])) : [];
	}
	
	/**
	 * Get results as array
	 * 
	 * @return array
	 */
	public function toArray(): array
	{
		return $this->sthSelect->fetchAll(PDO::FETCH_ASSOC);
	}

}