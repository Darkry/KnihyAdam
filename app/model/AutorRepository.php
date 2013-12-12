<?php
namespace Knihovna;

class AutorRepository extends Repository
{

	public function addAuthor($name, $dilo) {
		$this->getTable()->insert(array("jmeno" => $name, "dilo" => $dilo));
	}

	public function getAllAuthors() {
		return $this->getTable()->order("jmeno");
	}

	public function getAuthor($id) {
		return $this->getTable()->find($id)->fetch();
	}
	
}