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

	public function getAllBorrowedBooksInfo($id) {
		$books = array();
		foreach($this->getTable()->find($id)->fetch()->related("pujceno")->order("do") as $pujceno) {
			$kniha = array();
			$kniha["pujcenoDo"] = $pujceno->do;
			$kniha["autor"] = $pujceno->ref("kniha")->ref("autor")->jmeno . " " . $pujceno->ref("kniha")->ref("autor")->prijmeni;
			$kniha["autorId"]= $pujceno->ref("kniha")->ref("autor")->id;
			$kniha["nazev"] = $pujceno->ref("kniha")->nazev;
			$kniha["knihaId"] = $pujceno->ref("kniha")->id;
			$books[] = $kniha;
		}
		return $books; 
	}
}