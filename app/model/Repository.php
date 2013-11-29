<?php
namespace Knihovna;


abstract class Repository extends \Nette\Object
{

	/** @var Nette\Database\Connection */
	protected $connection;


	public function __construct(\Nette\Database\Connection $db) {
		$this->connection = $db;
	}

	protected function getTable()
	{
		preg_match("#(\w+)Repository$#", get_class($this), $m);
		return $this->connection->table(lcfirst($m[1]));
	}

	public function findAll() {
		return $this->getTable();
	}

	public function findBy(array $by) {
		return $this->getTable()->where($by);
	}

}