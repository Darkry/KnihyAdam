<?php
namespace Knihovna;

class AutorRepository extends Repository
{

	public function addAuthor($name, $prijmeni) {
		$this->getTable()->insert(array("jmeno" => $name, "prijmeni" => $prijmeni));
	}

	public function getAllAuthors() {
		return $this->getTable()->order("jmeno");
	}

	public function getAuthor($id) {
		return $this->getTable()->find($id)->fetch();
	}

	public function getAuthorBooksCount($id) {
		return $this->getTable()->find($id)->fetch()->related("kniha")->count();
	}

	public function getAllAuthorBooks($id) {
		return $this->getTable()->find($id)->fetch()->related("kniha");
	}

	public function deleteAuthor($id) {
		$this->getTable()->find($id)->delete();
	}
	
	public function editAuthor($name, $prijmeni, $id) {
		$this->getTable()->find($id)->fetch()->update(array("jmeno" => $name, "prijmeni" => $prijmeni));
	}
}