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
	
	public function getReaderBooksCount($id) {
		return $this->getTable()->find($id)->fetch()->related("pujceno")->count();
	}

	public function deleteReader($id) {
		$this->getTable()->find($id)->delete();
	}
	
	public function editReader($name, $email, $id) {
		$this->getTable()->find($id)->fetch()->update(array("jmeno" => $name, "email" => $email));
	}
}