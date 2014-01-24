<?php
namespace Knihovna;

class KnihaRepository extends Repository
{
	
	public function addBook($nazev, $autor, $zanr, $vytisky) {
		$this->getTable()->insert(array("nazev" => $nazev, "celkemVytisku" => $vytisky, "volnychVytisku" => $vytisky, "autor_id" => $autor, "zanr_id" => $zanr));
	}

	public function getAllBooks() {
		return $this->getTable()->order("nazev");
	}

	public function getBook($id) {
		return $this->getTable()->find($id)->fetch();
	}

	public function editBook($id, $nazev, $autor, $zanr) {
		$this->getTable()->find($id)->fetch()->update(array("nazev" => $nazev, "autor_id" => $autor, "zanr_id" => $zanr));
	}

	public function getFreeCopiesCount($id) {
		return $this->getTable()->find($id)->fetch()->volnychVytisku;
	}

	public function increaseCopiesNumber($by, $id) {
		$row = $this->getTable()->find($id)->fetch();
		$row->update(array("celkemVytisku" => $row->celkemVytisku + $by, "volnychVytisku" => $row->volnychVytisku + $by));
	}

}