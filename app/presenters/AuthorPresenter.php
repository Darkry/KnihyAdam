<?php
use \Nette\Application\UI\Form;
use \Nette\Utils\Html;

class AuthorPresenter extends BasePresenter
{

	private $cModel;
	private $genreModel;
	private $bookModel;

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

	public function createComponentEditAuthorForm() {
		$formAutori = new Form();

		$formAutori->addText("name", "Jméno: ")->setRequired("Prosím, vyplňte jméno autora.")
										 ->setAttribute('placeholder', 'Jméno autora...');
		$formAutori->addText("prijmeni", "Příjmení: ")->setRequired("Prosím, vyplňte příjmení autora.")
										 ->setAttribute('placeholder', 'Příjmení autora...');

		$id = $this->getParameter("id");
		$data = $this->cModel->getAuthor($id);
		$formAutori->setDefaults(array('name' => $data->jmeno, 'prijmeni' => $data->prijmeni));

		$formAutori->addHidden("id", $id);
		$formAutori->addSubmit("submit", "Upravit údaje");

		$formAutori->onSuccess[] = callback($this, "editAuthorFormSubmitted");

		return $formAutori;
	}

	public function addAuthorFormSubmitted(Form $form) {
		$val = $form->getValues();

		$this->cModel->addAuthor($val->name, $val->prijmeni);
		$this->flashMessage("Autor byl úspěšně přidán.", "success");
		$this->redirect("default");
	}

	public function editAuthorFormSubmitted(Form $form) {
		$val = $form->getValues();

		$this->cModel->editAuthor($val->name, $val->prijmeni, $val->id);
		$this->flashMessage("Údaje byly úspěšně změněny.", "success");
		$this->redirect("this");
	}

	public function renderDetail($id) {
		$author = $this->cModel->getAuthor($id);
		$this->template->jmeno = $author->jmeno;
		$this->template->prijmeni = $author->prijmeni;
		$this->template->id = $author->id;
		$this->template->knihy = $this->cModel->getAllAuthorBooks($id);
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