<?php
namespace Knihovna;

class ZanrRepository extends Repository
{
	
	public function addGenre($name, $icon) {
		$this->getTable()->insert(array("nazev" => $name, "ikona" => $icon));
	}

}