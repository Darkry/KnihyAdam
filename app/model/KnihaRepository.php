<?php
namespace 	Knihovna;

class KnihaRepository extends Repository
{
	
	public function addBook($nazev, $autor, $zanr, $vytisky) {
		$this->getTable()->insert(array("nazev" => $nazev, "celkemVytisku" => $vytisky, "volnychVytisku" => $vytisky, "autor_id" => $autor, "zanr_id" => $zanr));
	}

}