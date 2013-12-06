<?php
namespace Knihovna;

class CtenarRepository extends Repository
{

	public function addUser($name, $email) {
		$this->getTable()->insert(array("jmeno" => $name, "email" => $email));
	}

	public function getAllUsers() {
		return $this->getTable()->order("jmeno");
	}

	public function getUser($id) {
		return $this->getTable()->find($id)->fetch();
	}
	
}