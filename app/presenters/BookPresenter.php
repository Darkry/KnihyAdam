<?php
use \Nette\Application\UI\Form;
use \Nette\Utils\Html;

class BookPresenter extends BasePresenter
{

	private $authorModel;
	private $genreModel;
	private $bookModel;

	public function startup() {
		parent::startup();
		$this->authorModel = $this->getService("autorRepository");
		$this->bookModel = $this->getService("knihaRepository");
		$this->genreModel = $this->getService("zanrRepository");
	}

	public function renderDefault() {
		$books = $this->bookModel->getAllBooks();
		$this->template->knihy = $books;

	}

	public function createComponentAddBookForm() {
		$form = new Form();

		$form->addText("name", "Název: ")->setRequired("Vyplňte, prosím, název knihy.");
		$form->addText("number", "Počet kopií: ")->setRequired("Prosíme, vyplňte počet kopií knihy, které knihovna vlastní.")
												 ->addRule(Form::FLOAT, "Hodnota počtu výtisků musí být číslo.")
												 ->setType("number");

		$authorList = $this->authorModel->getAllAuthors();
		$authors = array();
		foreach($authorList as $author) {
			$authors[$author->id] = $author->jmeno . " " . $author->prijmeni;
		}
		$form->addSelect("author", "Autor: ", $authors)->setPrompt("Vyberte autora")->setRequired("Prosíme, vyberte autora knihy.");


		$genreList = $this->genreModel->getAllGenres();
		$genres = array();
		foreach($genreList as $genre) {
			$genres[$genre->id] = $genre->nazev;
		}
		$form->addSelect("genre", "Žánr:: ", $genres)->setPrompt("Vyberte žánr")->setRequired("Prosíme, vyberte žánr knihy.");

		$form->addSubmit("submit", "Přidat knihu");

		$form->onSuccess[] = callback($this, "addBookFormSubmitted");

		return $form;
	}

	public function addBookFormSubmitted(Form $form) {

		$val = $form->getValues();
		$this->bookModel->addBook($val->name, $val->author, $val->genre, $val->number);
		$this->flashMessage("Kniha byla úspěšně přidána.");
		$this->redirect("this");
	}
}
