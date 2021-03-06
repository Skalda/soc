<?php

namespace Model;

use	\Nette,
	\Nette\Database\Table\ActiveRow,
	\Nette\Database\Table\Selection;



/**
 * Represents repository for database table
 */
abstract class Table extends Nette\Object
{
	/** @var Nette\Database\Connection */
	protected $connection;
	



	/**
	 * @param  Nette\Database\Connection
	 * @throws \NetteAddons\InvalidStateException
	 */
	public function __construct(Nette\Database\Connection $db)
	{
		if (!isset($this->tableName)) {
			$class = get_called_class();
			throw new InvalidStateException("Property \$tableName must be defined in $class.");
		}

		$this->connection = $db;
	}



	/**
	 * @return \Nette\Database\Table\Selection
	 */
	protected function getTable()
	{
		return $this->connection->table($this->tableName);
	}



	/**
	 * @return \Nette\Database\Table\Selection
	 */
	public function findAll()
	{
		return $this->getTable();
	}



	/**
	 * @param  array
	 * @return \Nette\Database\Table\Selection
	 */
	public function findBy(array $by)
	{
		return $this->getTable()->where($by);
	}



	/**
	 * @param  array
	 * @return \Nette\Database\Table\ActiveRow|FALSE
	 */
	public function findOneBy(array $by)
	{
		return $this->findBy($by)->limit(1)->fetch();
	}



	/**
	 * @param  int
	 * @return \Nette\Database\Table\ActiveRow|FALSE
	 */
	public function find($id)
	{
		return $this->getTable()->get($id);
	}



	/**
	 * Creates and inserts new row to database.
	 *
	 * @param  array row values
	 * @return \Nette\Database\Table\ActiveRow created row
	 * @throws \Model\DuplicateEntryException
	 * @throws \PDOException in case of SQL / database error
	 */
	protected function createRow(array $values)
	{
		try {
			return $this->getTable()->insert($values);
		} catch (\PDOException $e) {
			if (is_array($e->errorInfo) && $e->errorInfo[1] == 1062) {
				throw new DuplicateEntryException($e->getMessage(), $e->errorInfo[1], $e);
			} else {
				throw $e;
			}
		}
	}


}