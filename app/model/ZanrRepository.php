<?php
namespace Knihovna;

class ZanrRepository extends Repository
{
	
	public function addGenre($name, $icon) {
		$this->getTable()->insert(array("nazev" => $name, "ikona" => $icon));
	}

	public function getAllGenres() {
		return $this->getTable()->order("nazev");
	}

	public function getGenre($id) {
		return $this->getTable()->find($id)->fetch();
	}

	public function editGenre($name, $id) {
		$this->getTable()->find($id)->fetch()->update(array("nazev" => $name));
	}

	public function deleteGenre($id) {
		$this->getTable()->find($id)->delete();
	}

	public function getGenreBooksCount($id) {
		return $this->getTable()->find($id)->fetch()->related("kniha")->count();
	}

	public function getAllGenreBooks($id) {
		return $this->getTable()->find($id)->fetch()->related("kniha");
	}
}