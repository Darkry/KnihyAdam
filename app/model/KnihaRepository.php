<?php
namespace 	Knihovna;

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

}