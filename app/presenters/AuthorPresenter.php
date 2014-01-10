<?php
use \Nette\Application\UI\Form;
use \Nette\Utils\Html;

class AuthorPresenter extends BasePresenter
{

	private $cModel;

	public function startup() {
		parent::startup();
		$this->cModel = $this->getService("autorRepository");
	}

	public function renderDefault() {
		$authors = $this->cModel->getAllAuthors();
		$this->template->autori = $authors;
	}

	public function createComponentAddAuthorForm() {
		$formAutori = new Form();

		$formAutori->addText("name", "Jméno: ")->setRequired("Prosím, vyplňte jméno autora.")
										 ->setAttribute('placeholder', 'Jméno autora...');
		$formAutori->addText("prijmeni", "Příjmení: ")->setRequired("Prosím, vyplňte příjmení autora.")
										   ->setAttribute('placeholder', 'Příjmení autora...');


		$formAutori->addSubmit("submit", "Přidat autora");

		$formAutori->onSuccess[] = callback($this, "addAuthorFormSubmitted");

		return $formAutori;
	}

	public function addAuthorFormSubmitted(Form $form) {
		$val = $form->getValues();

		$this->cModel->addAuthor($val->name, $val->prijmeni);
		$this->flashMessage("Autor byl úspěšně přidán.", "success");
		$this->redirect("default");
	}

	public function renderDetail($id) {
		$author = $this->cModel->getAuthor($id);
		$this->template->jmeno = $author->jmeno;
		$this->template->prijmeni = $author->prijmeni;
		$this->template->id = $author->id;
	}

	public function handleDeleteAuthor($delId) {
		if($this->cModel->getAuthorBooksCount($delId) > 0) {
			$this->flashMessage("Autor nemohl být smazán, protože v naší databázi jsou od něj uloženy nějaké knihy.", "error");
			$this->redirect("this");
		}
		else {
			$this->cModel->deleteAuthor($delId);
			$this->flashMessage("Autor byl úspěšně smazán z naší databáze.", "success");
			$this->redirect("default");
		}
	}

}