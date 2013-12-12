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
		$formAutori->addText("dilo", "Dílo: ")->setRequired("Prosím, vyplňte dílo autora.")
										   ->setAttribute('placeholder', 'Dílo autora...');


		$formAutori->addSubmit("submit", "Přidat autora");

		$formAutori->onSuccess[] = callback($this, "addAuthorFormSubmitted");

		return $formAutori;
	}

	public function addAuthorFormSubmitted(Form $form) {
		$val = $form->getValues();

		$this->cModel->addAuthor($val->name, $val->dilo);
		$this->flashMessage("Autor byl úspěšně přidán.", "success");
		$this->redirect("default");
	}

	public function renderDetail($id) {
		$author = $this->cModel->getAuthor($id);
		$this->template->jmeno = $author->jmeno;
		$this->template->dilo = $author->dilo;
	}

}