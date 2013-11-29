<?php
namespace Knihovna;

class CtenarRepository extends Repository
{

	public function addUser($name, $email) {
		$this->getTable()->insert(array("jmeno" => $name, "email" => $email));
	}
	
}